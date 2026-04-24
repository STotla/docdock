<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // create an admin user
      
        //$this->call(RolesPermissionAdminSeeder::class);
        //$this->call(SpecializationsSeeder::class);


         $this->call(CountryStateCityTableSeeder::class);

       // $this->call(DoctorSeeder::class);
       //$this->call(DoctorSessionSeeder::class);
       // $this->call(PatientSeeder::class);
       //$this->call(AppointmentAndReviewSeeder::class);
    }
}
