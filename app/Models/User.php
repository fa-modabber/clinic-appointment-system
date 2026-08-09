<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Policies\UserPolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;
    use HasRoles;
    protected $table = "users";
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'mobile',
        'first_name',
        'last_name',
        'password',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }


    // roles
    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    public function isPatient(): bool
    {
        return $this->hasRole('patient');
    }

    public function isDoctor(): bool
    {
        return $this->hasRole('doctor');
    }

    public function isStaff(): bool
    {
        return $this->hasRole('staff');
    }

    // Authentication
    public function canLoginWithOtp(): bool
    {
        return $this->isPatient();
    }

    public function canLoginWithPassword(): bool
    {
        return ! $this->isPatient();
    }

    public function canResetPassword(): bool
    {
        return $this->canLoginWithPassword();
    }

    //other
    public function isActive(): bool
    {
        return $this->is_active;
    }
}
