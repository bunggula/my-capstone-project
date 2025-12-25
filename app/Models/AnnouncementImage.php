<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnouncementImage extends Model
{
    protected $fillable = ['announcement_id', 'path'];

    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }
    public function images()
{
    return $this->hasMany(AnnouncementImage::class);
}
}