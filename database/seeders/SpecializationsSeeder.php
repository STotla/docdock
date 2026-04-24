<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpecializationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specializations = [
            'Cardiology',
            'Dermatology',
            'Endocrinology',
            'Gastroenterology',
            'Hematology',
            'Neurology',
            'Oncology',
            'Pediatrics',
            'Psychiatry',
            'Radiology',
        ];

        foreach ($specializations as $specialization) {
            \App\Models\Specialization::firstOrCreate(['name' => $specialization,  'icon' => 'doctor-icons/'.$specialization.'.png', 'is_active' => true,'description' => 'Description for '.$specialization]);
        }
    }
}
