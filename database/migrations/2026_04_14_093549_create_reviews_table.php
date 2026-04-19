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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->foreignId('doctor_id')
                  ->constrained('doctors')
                  ->cascadeOnDelete();

            $table->foreignId('appointment_id')
                  ->constrained('appointments')
                  ->cascadeOnDelete();

            $table->unsignedTinyInteger('rating')
                  ->comment('1 to 5 stars');

            $table->text('review')->nullable();

            $table->enum('status', ['pending', 'approved', 'rejected'])
                  ->default('approved');

            $table->timestamps();

            $table->unique('appointment_id');

            // ── Useful indexes ──
            $table->index('doctor_id');
            $table->index('user_id');
            $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
