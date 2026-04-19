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
            $table->text('appointment_id')->unique()->after('id');
            $table->unsignedInteger('amount')->nullable()->after('appointment_end_time');
            $table->string('currency')->default('INR')->after('amount');
            $table->string('payment_status')->default('pending')->after('currency'); // pending, paid, failed
            $table->unsignedInteger('payment_retry_count')->default(0)->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn([
                'appointment_id',
                'amount',
                'currency',
                'payment_status',
                'payment_retry_count',
            ]);
        });
    }
};
