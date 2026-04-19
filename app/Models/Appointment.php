<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    //

    protected $fillable = [
        'user_id',
        'doctor_id',
        'appointment_id',
        'doctor_session_instance_id',
        'appointment_date',
        'appointment_start_time',
        'appointment_end_time',
        'name',
        'email',
        'phone',
        'gender',
        'age',
        'note',
        'status',
        'amount',
        'currency',
        'payment_status',
        'payment_retry_count',
    ];

    protected $casts = [
        'appointment_time' => 'datetime',
        'amount' => 'integer',
        'payment_retry_count' => 'integer',
    ];


    protected static function booted()
    {
        static::creating(function ($appointment) {
            $year = date('Y');

            $count = static::where('doctor_id', $appointment->doctor_id)
                ->whereYear('created_at', $year)
                ->count() + 1;
            $appointment->appointment_id = sprintf(
                'DOCK-%s-%03d-%04d',
                $year,
                $appointment->doctor_id,
                $count
            );
        });
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
    public function doctorSessionInstance()
    {
        return $this->belongsTo(DoctorSessionInstance::class, 'doctor_session_instance_id');
    }
    public function patient()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function markAsCompleted($diagnosisNote = null)
    {

        if (!empty($diagnosisNote)) {
            $this->doctor_notes = $diagnosisNote;
        }
        $this->status = 'completed';
        $this->save();
    }

    protected function appointmentslipUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->payment_status !== 'paid') {
                    return null;
                }

                return asset("storage/private/appointments/{$this->user_id}/{$this->appointment_id}.pdf");
            },
        );
    }

    public function review()
    {

        return $this->hasOne(Review::class);
    }

    public function canBeReviewed()
    {
        return $this->status === 'completed' && is_null($this->review);
    }

}
   
