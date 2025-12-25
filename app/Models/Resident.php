<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResidentResetPasswordNotification;


class Resident extends Authenticatable implements CanResetPassword
{
    use HasApiTokens, HasFactory, CanResetPasswordTrait, Notifiable;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'gender',
        'birthdate',
        'age',
        'civil_status',
        'category',
        'email',
        'phone',
        'barangay_id',
        'zone',
        'proof_of_residency',
        'password',
        'status',
        'reject_reason',
        'profile_picture',
        'voter',       // ✅ bago
        'proof_type', 
        'must_change_password', // ✅ plain column name lang dito
    ];
    
    protected $casts = [
        'must_change_password' => 'boolean', // ✅ dito mo ilagay yung type casting
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = ['full_name'];

    public function getFullNameAttribute()
    {
        return collect([
            $this->first_name,
            $this->middle_name,
            $this->last_name,
            $this->suffix,
        ])->filter()->implode(' ');
    }

    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }
    public function sendPasswordResetNotification($token)
{
    $this->notify(new ResidentResetPasswordNotification($token));
}
public function concerns()
{
    return $this->hasMany(Concern::class, 'resident_id');
}
// Resident.php
public function complainantBlotters() {
    return $this->hasMany(BlotterComplainant::class);
}

public function respondentBlotters() {
    return $this->hasMany(BlotterRespondent::class);
}

}
