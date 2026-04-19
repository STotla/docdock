<?php

namespace App\Listeners;

use App\Jobs\GenerateAppointmentSlip;
use App\Models\Appointment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Events\WebhookReceived;

class StripeWebhookListener
{
   
    /**
     * Handle the event.
     */
    public function handle(WebhookReceived $event): void
    {   
        $payload = $event->payload;
        $type =  $payload['type'] ?? '';
        $data = $payload['data']['object'] ?? [];
        Log::info('Stripe Webhook Data:', $data);

        $appointmentId = $data['metadata']['id'] ?? null;
        $appointment = Appointment::find($appointmentId);
        if(!$appointment) {
            return;
        }
        switch($type){
            case 'checkout.session.completed':

            if($data['payment_status'] === 'paid') {
                 $this->markAsConfirmed($appointment, $data['payment_intent']);

            }
            else{
                $appointment->update([
                    'payment_status' => 'pending',
                ]);
            }
            break;
             case 'checkout.session.async_payment_succeeded':
                $this->markAsConfirmed($appointment, $data['payment_intent']);
                break;
                 case 'charge.failed':
            case 'checkout.session.async_payment_failed':
                $appointment->update([
                    'status' => 'pending',
                    'payment_status' => 'failed',
                    'payment_retry_count' => $appointment->payment_retry_count + 1,
                ]);
                break;
        }
        
    }
     protected function markAsConfirmed($appointment,$transactionId)
    {
        $appointment->update([
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'transaction_id'=>$transactionId


        ]);
        GenerateAppointmentSlip::dispatch($appointment);
        
    }
}
