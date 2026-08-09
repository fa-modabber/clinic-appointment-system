<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;

// #[UsePolicy(PatientPolicy::class)]
class Patient extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'patients';
    protected $guarded = [];

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    protected static function booted(): void
    {
        static::creating(function (Patient $patient): void {
            $patient->ulid = (string) Str::ulid();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointmens()
    {
        return $this->hasMany(Appointment::class);
    }
}
