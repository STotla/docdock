<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RegisterDatabaseFunctions
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Register custom SQLite functions on every request
        if (DB::connection()->getDriverName() === 'sqlite') {
            try {
                DB::connection()->getPdo()->sqliteCreateFunction(
                    'haversine_distance',
                    function ($lat1, $lon1, $lat2, $lon2) {
                        if (!$lat1 || !$lon1 || !$lat2 || !$lon2) {
                            return null;
                        }

                        // Earth's radius in kilometers
                        $R = 6371;

                        // Convert to radians
                        $lat1 = deg2rad((float)$lat1);
                        $lon1 = deg2rad((float)$lon1);
                        $lat2 = deg2rad((float)$lat2);
                        $lon2 = deg2rad((float)$lon2);

                        // Haversine formula
                        $dlat = $lat2 - $lat1;
                        $dlon = $lon2 - $lon1;

                        $a = sin($dlat / 2) * sin($dlat / 2) +
                             cos($lat1) * cos($lat2) *
                             sin($dlon / 2) * sin($dlon / 2);

                        $c = 2 * asin(sqrt($a));

                        return $R * $c;
                    },
                    4 // Number of arguments
                );
            } catch (\Exception $e) {
                \Log::error("Failed to register haversine_distance: " . $e->getMessage());
            }
        }

        return $next($request);
    }
}
