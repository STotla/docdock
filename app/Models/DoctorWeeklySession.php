<?php

namespace App\Models;

use App\Services\DoctorSessionInstanceGenerator;
use Illuminate\Database\Eloquent\Model;

class DoctorWeeklySession extends Model
{
    protected $fillable = [
        'doctor_id',
        'day_of_week',
        'start_time',
        'end_time',
        'slots_count',
        'is_enabled',
    ];  
public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

public static function updateInstanceTable(Doctor $doctor){
     app(DoctorSessionInstanceGenerator::class)
            ->generateForNextDays($doctor, 60, regenerate: true);
}

    }


