<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorSessionInstance extends Model
{
    
    protected $fillable = [
        'doctor_id',
        'date',
        'start_time',
        'end_time',
        'capacity_total',
        'capacity_booked',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'capacity_total' => 'integer',
        'capacity_booked' => 'integer',
    ];

public function doctorWeeklySession()
    {
        return $this->belongsTo(DoctorWeeklySession::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

 


}
