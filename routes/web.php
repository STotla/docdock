<?php

use App\Http\Controllers\AppointmentSlipController;
use App\Http\Controllers\DoctorSignupController;
use App\Livewire\Doctor\Appointment\Verify;
use App\Livewire\Doctor\SearchDoctors;
use App\Livewire\Patient\Doctors\Index;
use App\Livewire\Patient\Welcome;
use App\Livewire\PatientDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', Welcome::class)->name('welcome');
Route::get('/doctor-portal', \App\Livewire\DoctorPortal::class)->name('doctor.portal');
Route::get('/find-doctors', SearchDoctors::class)->name('doctors.search');
Route::get('/doctors',Index::class)->name('doctors.index');

Route::get('/doctors/{doctor}', \App\Livewire\Patient\Doctors\Show::class)->name('doctors.show');
Route::get('/book-appointment/{doctor}/{date}', \App\Livewire\Patient\BookAppointment::class)->middleware(['auth'])->name('patient.appointments.book');
Route::get('/my-appointments', \App\Livewire\Patient\Appointments\Index::class)->middleware(['auth'])->name('patient.appointments');
Route::get('/my-appointments/{appointment}', \App\Livewire\Patient\Appointments\Show::class)->middleware(['auth'])->name('patient.appointments.show');
Route::get('/dashboard', PatientDashboard::class)->name('patient.dashboard')
    ->middleware(['auth', 'verified']);
   

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');



Route::get('/doctor/register', [DoctorSignupController::class, 'create'])->name('doctor.signup');
Route::post('/doctor/register', [DoctorSignupController::class, 'store'])->name('doctor.signup.store');

Route::get('/appointment/verify/{doctorId}/{appointmentId}', Verify::class)
    ->name('doctor.appointment.verify')
    ->middleware('auth:doctor');
    require __DIR__.'/auth.php';

    Route::get('/appointment/{appointment}/download-slip', [AppointmentSlipController::class, 'download'])
    ->name('appointment.slip.download')
    ->middleware('auth');