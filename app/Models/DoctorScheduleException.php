<?php

namespace App\Models;

use App\Casts\TimeCast;
use App\Enums\DoctorScheduleExceptionType;
use App\Policies\DoctorScheduleExceptionPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;


#[UsePolicy(DoctorScheduleExceptionPolicy::class)]
class DoctorScheduleException extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'doctor_schedule_exceptions';
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'type' => DoctorScheduleExceptionType::class,
        'start_time' => TimeCast::class,
        'end_time' => TimeCast::class,
    ];

    public function doctor()
    {
        return $this->belongsTo(
            Doctor::class
        );
    }

    public function scopeForDoctor(
        Builder $query,
        Doctor $doctor
    ): Builder {
        return $query->where('doctor_id', $doctor->id);
    }

    public function scopeForDate(
        Builder $query,
        string $date
    ): Builder {
        return $query->where('date', $date);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
