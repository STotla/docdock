<?php
namespace App\Listeners;

use App\Jobs\GenerateAppointmentSlip;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Events\WebhookReceived;

class StripeWebhookListener
{
    public function handle(WebhookReceived $event): void
    {   
        $payload = $event->payload;
        $type = $payload['type'] ?? '';
        $data = $payload['data']['object'] ?? [];
        
        // Find the user associated with this Stripe Customer ID
        $stripeCustomerId = $data['customer'] ?? null;
        $user = User::where('stripe_id', $stripeCustomerId)->first();

        if (!$user) {
            Log::warning('Stripe Webhook: No user found for Stripe ID ' . $stripeCustomerId);
            return;
        }

        // --- BRANCH BY ROLE ---

        if ($user->hasRole('patient')) {
            $this->handlePatientLogic($type, $data);
        } 
        
        elseif ($user->hasRole('doctor')) {
            $this->handleDoctorLogic($type, $data, $user);
        }
    }

    /**
     * Logic for Patients (Appointments)
     */
    protected function handlePatientLogic(string $type, array $data): void
    {
        $appointmentId = $data['metadata']['id'] ?? null;
        $appointment = Appointment::find($appointmentId);

        if (!$appointment) return;

        switch ($type) {
            case 'checkout.session.completed':
            case 'checkout.session.async_payment_succeeded':
                if ($data['payment_status'] === 'paid') {
                    $this->markAsConfirmed($appointment, $data['payment_intent'] ?? $data['id']);
                }
                break;

            case 'charge.failed':
            case 'checkout.session.async_payment_failed':
                $appointment->update([
                    'payment_status' => 'failed',
                    'payment_retry_count' => ($appointment->payment_retry_count ?? 0) + 1,
                ]);
                break;
        }
    }

    /**
     * Logic for Doctors (Subscriptions)
     */
    protected function handleDoctorLogic(string $type, array $data, User $user): void
    {
        switch ($type) {
            case 'checkout.session.completed':
                if ($data['payment_status'] === 'paid') {
                    Log::info("Doctor {$user->name} subscription payment completed.");
                    
                    // Add custom doctor logic here (e.g., unlocking premium features)
                }
                break;

            case 'customer.subscription.deleted':
                Log::info("Doctor {$user->name} subscription ended.");
                // Add logic for when the grace period ends and access is revoked
                break;
        }
    }

    protected function markAsConfirmed($appointment, $transactionId)
    {
        $appointment->update([
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'transaction_id' => $transactionId
        ]);
        
        GenerateAppointmentSlip::dispatch($appointment);
    }
}
