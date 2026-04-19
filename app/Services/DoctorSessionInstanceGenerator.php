<?php

namespace App\Services;

use App\Models\Doctor;
use App\Models\DoctorSessionInstance;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class DoctorSessionInstanceGenerator
{
    /**
     * Generate session instances for the next N days using ONLY weekly sessions.
     *
     * Safe regeneration rules:
     * - If $regenerate = true, we delete only instances that have no bookings (capacity_booked = 0)
     *   in the given date range, then recreate them.
     * - We never delete booked instances.a
     *
     * This is designed for your FCFS capacity booking (not time slots).
     */
    public function generateForNextDays(Doctor $doctor, int $days = 60, bool $regenerate = true): void
    {
        $from = CarbonImmutable::today();
        $to = $from->addDays($days);

        $this->generate($doctor, $from, $to, $regenerate);
    }

    /**
     * Generate instances for a date range [from..to].
     */
public function generate(Doctor $doctor, CarbonImmutable $from, CarbonImmutable $to, bool $regenerate = false): void
{
    DB::transaction(function () use ($doctor, $from, $to) {

        $weeklySessionsByDow = $doctor->weeklySessions()
            ->where('is_enabled', true)
            ->get()
            ->groupBy('day_of_week');

        $cursor = $from;

        while ($cursor->lte($to)) {
            $date = $cursor->toDateString();
            $dow = (int) $cursor->isoWeekday();

            $expected = collect($weeklySessionsByDow->get($dow, collect()))
                ->map(function ($s) use ($doctor, $date) {
                    return [
                        'doctor_id' => $doctor->id,
                        'date' => $date,
                        // Normalize to H:i to match database comparison
                        'start_time' => Carbon::parse($s->start_time)->format('H:i'),
                        'end_time' => Carbon::parse($s->end_time)->format('H:i'),
                        'capacity_total' => (int) $s->slots_count,
                    ];
                });

            $existing = DoctorSessionInstance::query()
                ->where('doctor_id', $doctor->id)
                ->where('date', $date)
                ->get()
                ->keyBy(function ($i) {
                    // Normalize database time to H:i for the key
                    $start = Carbon::parse($i->start_time)->format('H:i');
                    $end = Carbon::parse($i->end_time)->format('H:i');
                    return $start . '|' . $end;
                });

            foreach ($expected as $e) {
                $key = $e['start_time'] . '|' . $e['end_time'];
                $instance = $existing->get($key);

                if (! $instance) {
                    DoctorSessionInstance::create([
                        'doctor_id' => $doctor->id,
                        'date' => $date,
                        'start_time' => $e['start_time'],
                        'end_time' => $e['end_time'],
                        'capacity_total' => $e['capacity_total'],
                        'capacity_booked' => 0,
                        'status' => 'open',
                    ]);
                    continue;
                }

                $newTotal = $e['capacity_total'];
                if ($newTotal < $instance->capacity_booked) {
                    $newTotal = $instance->capacity_booked;
                }

                $instance->update([
                    'capacity_total' => $newTotal,
                    'status' => $instance->status === 'cancelled' ? 'cancelled' : 'open',
                ]);
            }

            // Cleanup Logic
            $expectedKeys = $expected->map(fn ($e) => $e['start_time'] . '|' . $e['end_time'])->all();

            foreach ($existing as $key => $instance) {
                if (!in_array($key, $expectedKeys, true)) {
                    if ($instance->capacity_booked > 0) {
                        $instance->update(['status' => 'closed']);
                    } else {
                        $instance->delete();
                    }
                }
            }

            $cursor = $cursor->addDay();
        }
    });
}

}