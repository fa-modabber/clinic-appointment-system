<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoctorSpecialty extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'doctor_specialty';
    protected $guarded = [];
}
