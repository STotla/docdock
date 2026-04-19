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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('specialization_id')->constrained()->onDelete('cascade')->nullable();
            $table->string('registration_no')->nullable();
            $table->string('qualification')->nullable();
            $table->text('profile_img_url')->nullable();
            $table->unsignedInteger('experience')->nullable();
            $table->text('bio')->nullable();
            $table->string('phone')->nullable();
            $table->string('clinic_name')->nullable();
            $table->string('clinic_address')->nullable();
            $table->string('city')->nullable();  
            $table->string('state')->nullable();
            $table->unsignedInteger('consultation_fee')->nullable();
            $table->string('currency')->default('INR'); 
            $table->string('profile_status')->default('draft');
            // approved, rejected, under review,draft
            $table->boolean('is_active')->default(true);
            $table->date('submitted_at')->nullable();
            $table->date('rejected_at')->nullable();

            $table->date('approved_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
