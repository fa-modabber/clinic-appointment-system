<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Specialty extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "specialties";
    protected $guarded = [];

    protected static function booted(): void
    {
        static::saving(function (Specialty $specialty) {
            $specialty->slug = Str::slug($specialty->title);
        });
    }

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'doctor_specialty');
    }
}
