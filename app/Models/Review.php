<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable=[
        'user_id',
        'doctor_id',
        'appointment_id',
        'rating',
        'review',
        'status',

    ];
    protected $casts = [
        'rating' => 'integer',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function doctor(){
        return $this->belongsTo(Doctor::class);
    }

    public function scopeApproved($query){
    return $query->where('status','approved');
    }
    public function scopeForApproved($query, int $doctorId){
    return $query->where('doctor_id',$doctorId);
    }
    
}
