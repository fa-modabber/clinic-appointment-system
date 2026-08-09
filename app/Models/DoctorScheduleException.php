<?php

namespace App\Models;

use App\Casts\TimeCast;
use App\Enums\DoctorScheduleExceptionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class DoctorScheduleException extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'doctor_schedule_exceptions';
    protected $guarded = [];

    protected $casts = [
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
}
