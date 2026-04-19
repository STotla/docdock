<?php

namespace App\Http\Middleware;

use App\Models\Doctor;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDoctorApprovedOrCompletingProfile
{
    public function handle(Request $request, Closure $next): Response
    { 
        // allow logout without any restrictions
        if ($request->routeIs('filament.doctor.auth.logout')) {
            return $next($request);
        }
        $user = $request->user();

        if (! $user || ! $user->hasRole('doctor')) {
            return abort(403);
        }

        $doctor = Doctor::where('user_id', $user->id)->first();

        // allow reaching the complete-profile page always
        if ($request->routeIs('filament.doctor.pages.complete-profile')) {
            return $next($request);
        }

        // if no profile yet or not approved -> force complete-profile
        if (! $doctor || $doctor->profile_status !== 'approved') {
            return redirect()->route('filament.doctor.pages.complete-profile');
        }

        return $next($request);
    }
}