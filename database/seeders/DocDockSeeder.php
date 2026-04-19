<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DocDockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
              app(PermissionRegistrar::class)->forgetCachedPermissions();

        Role::findOrCreate('admin');
        Role::findOrCreate('doctor');
        Role::findOrCreate('patient');

        $admin = User::firstOrCreate(
            ['email' => 'admin@docdock.test'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ],
        );

        $admin->assignRole('admin');
    }
}
