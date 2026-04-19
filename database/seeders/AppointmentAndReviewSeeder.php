<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AppointmentAndReviewSeeder extends Seeder
{
    public function run(): void
    {
        // Disable FK checks for the duration of this seeder.
        // SQLite enforces FKs strictly; we re-enable after all inserts.
        DB::statement('PRAGMA foreign_keys = OFF;');

        try {
            $this->seed();
        } finally {
            DB::statement('PRAGMA foreign_keys = ON;');
        }
    }

    private function seed(): void
    {
        // ----------------------------------------------------------------
        // 1. Fetch the 100 latest doctor IDs
        // ----------------------------------------------------------------
        $doctorIds = DB::table('doctors')
            ->orderByDesc('id')
            ->limit(100)
            ->pluck('id');

        if ($doctorIds->isEmpty()) {
            $this->command->warn('No doctors found. Skipping.');
            return;
        }

        // ----------------------------------------------------------------
        // 2. Fetch session instances (only columns we need)
        // ----------------------------------------------------------------
        $instances = DB::table('doctor_session_instances')
            ->whereIn('doctor_id', $doctorIds)
            ->get([
                'id', 'doctor_id', 'date',
                'start_time', 'end_time',
                'capacity_total', 'capacity_booked',
                'fee', 'amount', 'consultation_fee', 'currency',
            ]);

        if ($instances->isEmpty()) {
            $this->command->warn('No doctor session instances found. Skipping.');
            return;
        }

        // ----------------------------------------------------------------
        // 3. Load ALL patient users ONCE as plain stdClass rows
        // ----------------------------------------------------------------
        $patientRoleId = DB::table('roles')
            ->where('name', 'patient')
            ->value('id');

        if (! $patientRoleId) {
            $this->command->warn('"patient" role not found. Skipping.');
            return;
        }

        $patientUserIds = DB::table('model_has_roles')
            ->where('role_id', $patientRoleId)
            ->where('model_type', 'App\\Models\\User')
            ->pluck('model_id')
            ->toArray();

        if (empty($patientUserIds)) {
            $this->command->warn('No patient users found. Skipping.');
            return;
        }

        $patients     = DB::table('users')
            ->whereIn('id', $patientUserIds)
            ->get(['id', 'name', 'email', 'phone', 'gender'])
            ->toArray();

        $patientCount = count($patients);
        $patientIndex = 0;

        // ----------------------------------------------------------------
        // 4. Process each instance.
        //
        //    KEY FIX: reviews.appointment_id is a FK to appointments.id
        //    (the auto-increment integer), NOT to appointments.appointment_id
        //    (the UUID text column).
        //
        //    Strategy: for each slot, insert appointments one-by-one using
        //    insertGetId() to capture the integer PK, then immediately
        //    insert the matching review using that integer ID.
        //    This guarantees the FK is always satisfied and avoids any
        //    buffering ordering problem.
        // ----------------------------------------------------------------
        $today             = Carbon::today();
        $capacityIncrement = [];
        $totalAppointments = 0;
        $totalReviews      = 0;

        foreach ($instances as $instance) {

            $sessionDate = Carbon::parse($instance->date);
            $isPast      = $sessionDate->lt($today);

            $capacityTotal = max(1, (int) $instance->capacity_total);
            $alreadyBooked = max(0, (int) ($instance->capacity_booked ?? 0));
            $available     = max(0, $capacityTotal - $alreadyBooked);

            if ($available === 0) {
                continue;
            }

            [$fillMin, $fillMax] = $isPast
                ? [(int) ceil($available * 0.7), $available]
                : [(int) ceil($available * 0.3), (int) ceil($available * 0.7)];

            $bookCount = rand(max(1, $fillMin), max(1, $fillMax));
            $capacityIncrement[$instance->id] = $bookCount;

            $amount   = $instance->fee
                     ?? $instance->amount
                     ?? $instance->consultation_fee
                     ?? 500;
            $currency = $instance->currency ?? 'INR';
            $status   = $isPast ? 'completed' : 'confirmed';

            for ($b = 0; $b < $bookCount; $b++) {

                $patient = $patients[$patientIndex % $patientCount];
                $patientIndex++;

                // Insert appointment and capture the integer PK
                $appointmentIntId = DB::table('appointments')->insertGetId([
                    'user_id'                    => $patient->id,
                    'doctor_id'                  => $instance->doctor_id,
                    'doctor_session_instance_id' => $instance->id,
                    'name'                       => $patient->name,
                    'email'                      => $patient->email,
                    'phone'                      => $patient->phone ?? $this->fakePhone(),
                    'gender'                     => $patient->gender ?? $this->fakeGender(),
                    'note'                       => $isPast
                        ? 'Follow-up for routine check-up.'
                        : 'First consultation — please review recent reports.',
                    'status'                     => $status,
                    'age'                        => rand(18, 70),
                    'appointment_date'           => $sessionDate->toDateString(),
                    'appointment_start_time'     => $instance->start_time,
                    'appointment_end_time'       => $instance->end_time,
                    'doctor_notes'               => $isPast
                        ? 'Patient examined. Prescribed medication accordingly.'
                        : null,
                    'appointment_id'             => (string) Str::uuid(),
                    'amount'                     => $amount,
                    'currency'                   => $currency,
                    'payment_status'             => 'paid',
                    'payment_retry_count'        => 0,
                    'transaction_id'             => 'TXN-' . strtoupper(Str::random(10)),
                    'not_attended'               => 0,
                    'created_at'                 => $isPast
                        ? $sessionDate->copy()->subDays(rand(4, 10))
                        : now(),
                    'updated_at'                 => $isPast
                        ? $sessionDate->copy()
                        : now(),
                ]);

                $totalAppointments++;

                // Insert review immediately after its appointment using the
                // integer PK — FK is guaranteed to exist at this point
                if ($isPast) {
                    $reviewedAt = $sessionDate->copy()->addHours(rand(2, 8));

                    DB::table('reviews')->insert([
                        'user_id'        => $patient->id,
                        'doctor_id'      => $instance->doctor_id,
                        'appointment_id' => $appointmentIntId,
                        'rating'         => rand(3, 5),
                        'review'         => $this->fakeReview(),
                        'created_at'     => $reviewedAt,
                        'updated_at'     => $reviewedAt,
                    ]);

                    $totalReviews++;
                }
            }
        }

        // ----------------------------------------------------------------
        // 5. Update capacity_booked per instance
        // ----------------------------------------------------------------
        foreach ($capacityIncrement as $instanceId => $addedCount) {
            $current = (int) DB::table('doctor_session_instances')
                ->where('id', $instanceId)
                ->value('capacity_booked');

            DB::table('doctor_session_instances')
                ->where('id', $instanceId)
                ->update([
                    'capacity_booked' => $current + $addedCount,
                    'updated_at'      => now(),
                ]);
        }

        // ----------------------------------------------------------------
        // 6. Sync instance status flags
        // ----------------------------------------------------------------
        DB::table('doctor_session_instances')
            ->whereIn('doctor_id', $doctorIds)
            ->whereColumn('capacity_booked', '>=', 'capacity_total')
            ->update(['status' => 'booked', 'updated_at' => now()]);

        DB::table('doctor_session_instances')
            ->whereIn('doctor_id', $doctorIds)
            ->where('date', '<', $today->toDateString())
            ->update(['status' => 'closed', 'updated_at' => now()]);

        $this->command->info(sprintf(
            'Done — %d appointments, %d reviews, %d session instances updated.',
            $totalAppointments,
            $totalReviews,
            count($capacityIncrement)
        ));

    } // end seed()

    private function fakePhone(): string
    {
        return '9' . rand(100000000, 999999999);
    }

    private function fakeGender(): string
    {
        return rand(0, 1) ? 'male' : 'female';
    }

    private function fakeReview(): string
    {
        static $pool = [
            'Very professional and caring doctor. Highly recommended.',
            'Explained everything clearly. Felt very comfortable during the visit.',
            'Good experience overall. The doctor was attentive and thorough.',
            'Excellent consultation. Will definitely visit again.',
            'Doctor was patient and gave detailed advice. Very satisfied.',
            'Quick diagnosis and effective treatment. Thank you!',
            'Friendly staff and a knowledgeable doctor. Great experience.',
            'The doctor listened carefully and gave practical suggestions.',
            'Highly experienced and humble. Felt well taken care of.',
            'Prompt service and accurate diagnosis. Would recommend to everyone.',
            'The appointment was smooth and the doctor was very reassuring.',
            'Got the right treatment after a long time. Very grateful.',
        ];
        return $pool[array_rand($pool)];
    }
}
