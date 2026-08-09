<?php

namespace App\Models;

use App\Casts\TimeCast;
use App\Enums\Week;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class DoctorSchedule extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "doctor_schedules";
    protected $guarded = [];

    protected $casts = [
        'day_of_week' => Week::class,
        'start_time' => TimeCast::class,
        'end_time' => TimeCast::class,
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeForDoctor(
        Builder $query,
        Doctor $doctor
    ): Builder {
        return $query->where('doctor_id', $doctor->id);
    }

    public function scopeWeekday(
        Builder $query,
        Week $day
    ): Builder {
        return $query->where('day_of_week', $day->value);
    }

    // public function scopeValidForDate(Builder $query, Carbon|string $date): Builder
    // {
    //     return $query
    //         ->where('valid_from', '<=', $date)
    //         ->where(
    //             function ($query) use ($date) {
    //                 $query->whereNull('valid_until')
    //                     ->orWhere('valid_until', '>=', $date);
    //             }
    //         );
    // }
}
