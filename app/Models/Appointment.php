<?php

namespace App\Models;

use App\Casts\TimeCast;
use App\Enums\AppointmentStatus;
use App\Enums\Week;
use App\Policies\AppointmentPolicy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;


#[UsePolicy(AppointmentPolicy::class)]
class Appointment extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "appointments";
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status' => AppointmentStatus::class,
            'start_time' => TimeCast::class,
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    protected static function booted(): void
    {
        static::creating(function (Appointment $appointment): void {
            $appointment->ulid = (string) Str::ulid();
        });
    }

    // Relations
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    //Scopes
    public function scopeStatus(Builder $query, AppointmentStatus $status): Builder
    {
        return $query->where('status', $status->value);
    }

    public function scopeForPatient(Builder $query, Patient $patient): Builder
    {
        return $query->where('patient_id', $patient->id);
    }

    public function scopeForDoctor(Builder $query, Doctor $doctor): Builder
    {
        return $query->where('doctor_id', $doctor->id);
    }

    public function scopeConfirmed(Builder $query): Builder
    {
        return $query->where('status', AppointmentStatus::CONFIRMED->value);
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query
            ->confirmed()
            ->where('start_datetime', '>', now())
            ->where('cancelled_at', null);
    }

    public function scopePast(Builder $query): Builder
    {
        return $query
            ->confirmed()
            ->where('start_datetime', '<', now());
    }

    public function scopeDate(Builder $query, Carbon|string $date): Builder
    {
        return $query->whereDate('start_datetime', $date);
    }

    public function scopeBetween(
        Builder $query,
        Carbon|string $from,
        Carbon|string $to
    ): Builder {
        return $query->whereBetween('start_datetime', [
            $from,
            $to
        ]);
    }

    public function scopeWeekday(
        Builder $query,
        Week $day
    ): Builder {
        return $query->whereRaw(
            'DAYOFWEEK(start_datetime) = ?',
            [$day->mysqlValue()]
        );
    }
}
