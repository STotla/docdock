<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DoctorSignupController extends Controller
{
    public function create(){
        return view('doctor.signup');
    }

    public function store(Request $request){
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            // Add other doctor-specific fields as needed
        ]);

        DB::transaction(function () use ($validatedData, &$user) {
        // Create the user and doctor profile
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        // Assign the doctor role to the user
        $user->assignRole('doctor');

        // Create a doctor profile linked to the user
        Doctor::create([
            'user_id' => $user->id,
            'specialization_id' => 1, // Set this based on your specialization logic
            // Add other doctor-specific fields as needed
        ]);
        });

         auth()->login($user);
            return redirect()->route('filament.doctor.pages.dashboard');

        // Redirect to a success page or dashboard
    }
}
