<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoctorSchedule extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "doctor_schedules";
    protected $guarded = [];

    public function timeSlots()
    {
        return $this->hasMany(TimeSlot::class);
    }
}
