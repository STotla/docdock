<?php

namespace App\Jobs;

use App\Models\Appointment;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CleanupMissedAppointments implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Appointment::whereDate('appointment_date','<',Carbon::today())
                    ->where('status','confirmed')
                    ->update([
                        'status'=>'completed',
                        'not_attended'=>1
                    ]);
                    
    }
}
