<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\DoctorWeeklySession;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                // 1. Get the last 100 doctors created
        $doctors = Doctor::latest()->take(100)->get();

        if ($doctors->isEmpty()) {
            $this->command->error('No doctors found. Please run DoctorSeeder first.');
            return;
        }

        $this->command->info('Seeding weekly sessions for 100 doctors...');

        foreach ($doctors as $doctor) {
            // Pick 3-5 random weekdays (Monday to Friday) to have sessions
            $weekDays = collect([1, 2, 3, 4, 5])->random(rand(3, 5));

            foreach ($weekDays as $day) {
                // Each doctor will have 2-4 slots per day
                $slotsPerDay = rand(2, 4);
                
                // Track occupied hours to prevent collision: [start_hour, end_hour]
                $occupiedRanges = [];

                for ($i = 0; $i < $slotsPerDay; $i++) {
                    $attempts = 0;
                    $foundSlot = false;

                    while (!$foundSlot && $attempts < 10) {
                        // Generate a random start hour between 08:00 and 18:00
                        $startHour = rand(8, 18);
                        $endHour = $startHour + 1; // Each session is 1 hour long for simplicity

                        // Check for collision with existing ranges for this doctor on this day
                        $collides = false;
                        foreach ($occupiedRanges as $range) {
                            if ($startHour < $range['end'] && $endHour > $range['start']) {
                                $collides = true;
                                break;
                            }
                        }

                        if (!$collides) {
                            $startTime = Carbon::createFromTime($startHour, 0)->format('H:i:s');
                            $endTime = Carbon::createFromTime($endHour, 0)->format('H:i:s');

                            DoctorWeeklySession::create([
                                'doctor_id' => $doctor->id,
                                'day_of_week' => $day,
                                'start_time' => $startTime,
                                'end_time' => $endTime,
                                'slots_count' => rand(5, 10), // Capacity per session
                                'is_enabled' => true,
                            ]);
                            

                            $occupiedRanges[] = ['start' => $startHour, 'end' => $endHour];
                            $foundSlot = true;
                        }
                        $attempts++;
                    }
                }
            }

            // Optional: Trigger your instance generator for the next 60 days
            // DoctorWeeklySession::updateInstanceTable($doctor);
            $this->generateInstancesForSeeder($doctor);
        }

        $this->command->info('Weekly sessions seeded successfully.');
    }

    
    private function generateInstancesForSeeder($doctor)
{
    // Start from 30 days ago
    $from = \Carbon\CarbonImmutable::today()->subDays(30);
    
    // End 30 days from today (Total span of 60 days)
    $to = \Carbon\CarbonImmutable::today()->addDays(30);

    // Call your existing Service logic
    app(\App\Services\DoctorSessionInstanceGenerator::class)
        ->generate($doctor, $from, $to, regenerate: true);
}
}
