<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Doctor;
use App\Models\DoctorCertificate;
use App\Models\Specialization;
use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Fetch all specialisations
        $specs = Specialization::all();
        $doctorRole = Role::findByName('doctor');

        if ($specs->isEmpty()) {
            $this->command->error('No specializations found. Please seed SpecializationSeeder first.');
            return;
        }

        $this->command->info('Seeding 100 doctors...');

        // 2. Loop to create 100 entries
        for ($i = 1; $i <= 100; $i++) {
            
            // Create the User
            $user = User::create([
                'name' => fake()->name(),
                'email' => "doctor{$i}_" . Str::random(3) . "@docdock.com", // Unique emails
                'password' => Hash::make('password'),
            ]);
            $user->assignRole($doctorRole);

            // Create the Doctor Profile
           $doctor= Doctor::create([
                'user_id' => $user->id,
                'specialization_id' => $specs->random()->id, // Randomly assigned
                'registration_no' => 'MC-' . fake()->unique()->numberBetween(10000, 99999),
                'qualification' => fake()->randomElement(['MBBS, MD', 'MBBS, MS', 'BDS, MDS', 'MD - General Medicine']),
                'experience' => fake()->numberBetween(1, 35),
                'bio' => fake()->paragraph(3),
                'phone' => fake()->phoneNumber(),
                'clinic_name' => fake()->company() . ' Medical Center',
                'clinic_address' => fake()->streetAddress(),
                'city' => 'Bhilwara',
                'state' => 'Rajasthan',
                'consultation_fee' => fake()->randomElement([300, 500, 800, 1000, 1200, 1500]),
                'currency' => 'INR',
                'profile_status' => 'approved',
                'is_active' => true,
                'submitted_at' => now()->subDays(20),
                'approved_at' => now()->subDays(15),
                'profile_img_url' => 'doctor-profiles/im1.jpg', // Or use: 'https://pravatar.cc' . $user->email
            ]);

            DoctorCertificate::create([
                'doctor_id' => $doctor->id,
                'title' => 'Medical Board Certification',
                'file_path' => 'doctor-certificates/doc_certificate.pdf', // Fixed path as requested
                'issued_by' => fake()->randomElement(['Medical Council of India', 'State Medical Board', 'National Health Authority']),
                'issued_date' => now()->subYears(fake()->numberBetween(2, 10)),
                'expiry_date' => now()->addYears(5),
                'is_verified' => true,
                'verified_at' => now(),
            ]);
        }

        $this->command->info('100 doctors seeded successfully!');
    }
}
