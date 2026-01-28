<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoctorReview extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "doctor_reviews";
    protected $guarded = [];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function commenter()
    {
        return $this->belongsTo(User::class);
    }
}
