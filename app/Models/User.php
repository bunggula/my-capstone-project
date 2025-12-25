<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\VerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'email',
        'password',
        'role',
        'barangay_id',
        'gender',
        'birthday',
        'status',
        'start_year',  
        'end_year',    
    ];
    
    protected $appends = ['full_name'];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the user's initials.
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
            
    }
       // The barangay relationship method MUST be inside the class!
       public function barangay()
       {
           return $this->belongsTo(\App\Models\Barangay::class);
       }
       public function notifications()
{
    return $this->hasMany(\App\Models\Notification::class);
}
public function sendEmailVerificationNotification()
{
    $this->notify(new VerifyEmail);
}
public function proposals()
{
    return $this->hasMany(Proposal::class, 'captain_id');
}
public function getFullNameAttribute()
{
    return collect([
        $this->first_name,
        $this->middle_name,
        $this->last_name,
        $this->suffix,
    ])->filter()->implode(' ');
}

public function barangayInfo()
    {
        return $this->belongsTo(Barangay::class, 'barangay_id');
    }
   }


