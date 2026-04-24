<?php

namespace App\Http\Middleware;

use App\Models\Doctor;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDoctorApprovedAndSubscribed
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->routeIs('filament.doctor.auth.logout')) {
            return $next($request);
        }

        $user = $request->user();

        if (!$user || !$user->hasRole('doctor')) {
            return abort(403);
        }

        $doctor = $user->doctor; 
 
        if (!$doctor || $doctor->profile_status !== 'approved') {
            if ($request->routeIs('filament.doctor.pages.complete-profile')) {
                return $next($request);
            }
            return redirect()->route('filament.doctor.pages.complete-profile');
        }

       
        if (!$user->subscribed('doctor-subscription')) {
            if ($request->routeIs('filament.doctor.pages.subscription')) {
                return $next($request);
            }
            return redirect()->route('filament.doctor.pages.subscription')
                ->with('error', 'Please subscribe to access this feature.');
        }

        return $next($request);
    }
}
