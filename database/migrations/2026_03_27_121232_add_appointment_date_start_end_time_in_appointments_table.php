<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->date('appointment_date')->after('status')->nullable();
            $table->time('appointment_start_time')->after('appointment_date')->nullable();
            $table->time('appointment_end_time')->after('appointment_start_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('appointment_date');
            $table->dropColumn('appointment_start_time');
            $table->dropColumn('appointment_end_time');
        });
    }
};
