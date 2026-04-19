<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('doctor_day_overrides', function (Blueprint $table) {
            $table->id();

            $table->foreignId('doctor_id')
                ->constrained('doctors')
                ->cascadeOnDelete();

            $table->date('date')->index();

            // 'off' = no availability that day
            // 'custom' = use override sessions for that date
            $table->string('type'); // off|custom
            $table->text('note')->nullable();

            $table->timestamps();

            $table->unique(['doctor_id', 'date']);
            $table->index(['doctor_id', 'date', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_day_overrides');
    }
};