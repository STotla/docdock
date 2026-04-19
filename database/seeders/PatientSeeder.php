<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Fetch all specialisations
        $patientRole = Role::findByName('patient');


        $this->command->info('Seeding 300 patients...');

        // 2. Loop to create 100 entries
        for ($i = 1; $i <= 300; $i++) {
            
            // Create the User
            $user = User::create([
                'name' => fake()->name(),
                'email' => "patient{$i}_" . Str::random(3) . "@gmail.com", 
                'password' => Hash::make('password'),
                 'phone' => fake()->phoneNumber(),
                'dob' => fake()->date('Y-m-d', '-18 years'), 
                'gender' => fake()->randomElement(['male', 'female', 'other']),
                'address' => fake()->streetAddress(),
                'city' => 'Bhilwara',
                'state' => 'Rajasthan',
                'zipcode' => Str::substr(fake()->postcode(), 0, 10),
            ]);
            $user->assignRole($patientRole);

            // Create the Doctor Profile
                       
        }

        $this->command->info('300 patient seeded successfully!');
    }
}
