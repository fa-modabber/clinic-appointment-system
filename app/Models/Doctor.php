<?php

namespace App\Models;

use App\Policies\DoctorPolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Support\Str;


#[UsePolicy(DoctorPolicy::class)]
class Doctor extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "doctors";
    protected $guarded = [];

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    protected static function booted(): void
    {
        static::creating(function (Doctor $doctor): void {
            $doctor->ulid = (string) Str::ulid();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function specialties()
    {
        return $this->belongsToMany(
            Specialty::class,
            'doctor_specialty'
        );
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    public function scheduleExceptions()
    {
        return $this->hasMany(DoctorScheduleException::class);
    }

    public function appointments()
    {
        return $this->hasMany(
            Appointment::class
        );
    }
}
