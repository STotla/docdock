<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorCertificate extends Model
{
    protected $fillable = [
        'doctor_id',
        'title',
        'file_path',
        'issued_by',
        'issued_date',
        'expiry_date',
        'is_verified',
        'verified_at',
    ];
protected $casts = [
    'is_verified' => 'boolean',
    'verified_at' => 'datetime',
];
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }   

    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }
    
}
