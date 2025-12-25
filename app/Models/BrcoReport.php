<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrcoReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'barangay_id',   // ✅ idagdag mo to
        'location',
        'length',
        'date',
        'action_taken',
        'remarks',
        'conducted',
        'photo',
    ];
    public function photos()
{
    return $this->hasMany(BrcoReportPhoto::class);
}

}
