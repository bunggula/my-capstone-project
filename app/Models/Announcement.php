<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Announcement;


class Announcement extends Model
{
    protected $fillable = [
        'title',
        'details',
        'date',
        'time',
        'target',
        'barangay_id',
        'target_role',
        'posted_by',
    ];
    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }
    public function images()
    {
        return $this->hasMany(AnnouncementImage::class);
    }
}
