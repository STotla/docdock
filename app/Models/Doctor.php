<?php

namespace App\Models;

use App\Observers\DoctorObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

#[ObservedBy([DoctorObserver::class])]
class Doctor extends Model
{
    //

    use Notifiable;
    protected $fillable = [
        'user_id',
        'specialization_id',
        'bio',
        'phone',
        'registration_no',
        'status',
        'qualification',
        'profile_img_url',
        'experience',
        'clinic_name',
        'clinic_address',
        'city',
        'state',
        'consultation_fee',
        'currency',
        'profile_status',
        'latitude',
        'longitude',


    ];
    protected $appends = ['average_rating', 'rating_count'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }
    public function certificates()
    {
        return $this->hasMany(DoctorCertificate::class);
    }
    public function certificate($certificateId)
    {
        return $this->certificates()->where('id', $certificateId)->first();
    }

    public function weeklySessions()
    {
        return $this->hasMany(DoctorWeeklySession::class);
    }
    public function sessionInstances()
    {
        return $this->hasMany(DoctorSessionInstance::class);
    }

    public function approved()
    {
        return $this->status === 'approved';
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }


    public function reviews(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Review::class);
    }

    protected function averageRating(): Attribute
    {
        return Attribute::get(fn() => (float) round(
            $this->reviews()->approved()->avg('rating') ?? 0,
            1
        ));
    }

    public function ratingCount(): Attribute
    {
        return Attribute::get(fn() =>
        $this->reviews()->approved()->count());
    }


public function scopeActive(Builder $query): Builder
{
    return $query->where('profile_status', 'approved')
        ->whereHas('user.subscriptions', function (Builder $q) {
            
            $q->active() 
            ->where('type', 'doctor-subscription') 
              ->where(function (Builder $dateCheck) {
                  $dateCheck->whereNull('ends_at')
                            ->orWhere('ends_at', '>', now());
              });
        });
}

}
