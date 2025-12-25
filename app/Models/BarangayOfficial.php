<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangayOfficial extends Model
{
    use HasFactory;

    protected $fillable = [
        'barangay_id',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'gender',
        'birthday',
        'civil_status',
        'phone',
        'email',
        'position',
        'start_year',
        'end_year',
        'categories',
    ];
    

    protected $casts = [
        'categories' => 'array',
        'birthday' => 'date',
       
    ];

    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }

    public function getAgeAttribute()
    {
        return $this->birthday ? $this->birthday->age : null;
    }
    public function user()
{
    return $this->belongsTo(User::class);
}

}
