<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Register custom distance function for SQLite
        if (DB::connection()->getDriverName() === 'sqlite') {
            DB::connection()->getPdo()->sqliteCreateFunction(
                'haversine_distance',
                function ($lat1, $lon1, $lat2, $lon2) {
                    // Earth's radius in kilometers
                    $R = 6371;

                    // Convert to radians
                    $lat1 = deg2rad($lat1);
                    $lon1 = deg2rad($lon1);
                    $lat2 = deg2rad($lat2);
                    $lon2 = deg2rad($lon2);

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
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // SQLite doesn't support dropping functions in migrations
    }
};
