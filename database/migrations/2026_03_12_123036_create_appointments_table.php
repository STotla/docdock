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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_session_instance_id')->constrained()->onDelete('cascade');
            $table->dateTime('appointment_time');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('gender')->nullable();
            $table->text('note')->nullable();
            $table->string('status')->default('booked'); // booked | cancelled | completed
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
