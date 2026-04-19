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
        Schema::create('doctor_override_sessions', function (Blueprint $table) {
            $table->id();
           $table->foreignId('doctor_day_override_id')
                ->constrained('doctor_day_overrides')
                ->cascadeOnDelete();

            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedSmallInteger('slots_count');

            $table->timestamps();

            $table->index(['doctor_day_override_id', 'start_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_override_sessions');
    }
};
