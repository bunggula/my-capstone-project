<?php
namespace App\Models;
use App\Models\EventImage;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'barangay_id',
        'title',
        'date',
        'time',
        'venue',
        'details',
        'status',
        'rejection_reason', 
        'rejection_notes',
        'posted_by',  
    ];

    public function images()
    {
        return $this->hasMany(EventImage::class);
    }

    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }

    public function eventImages()
{
    return $this->hasMany(EventImage::class);
}

}
