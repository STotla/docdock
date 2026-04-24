<?php

namespace App\Providers;

use Illuminate\Database\Events\Connections\ConnectionEstablished;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Listen for new database connections
        DB::listen(function ($query) {
            // This will be called for each query, but we need to register the function once
        });

        // Register function on initial connection
        $this->registerHaversineFunction();

        // Register function whenever a new connection is established
        if (method_exists(DB::connection(), 'listen')) {
            DB::connection()->listen(function () {
                $this->registerHaversineFunction();
            });
        }
    }

    private function registerHaversineFunction(): void
    {
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
                    4
                );
            } catch (\Exception $e) {
                \Log::error("Failed to register haversine_distance function: " . $e->getMessage());
            }
        }
    }
}
