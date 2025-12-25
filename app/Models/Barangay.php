<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barangay extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'chairperson',
        'secretary',
        'contact_number',
        'email',
        'address',
        'office_hours',
        'municipality_id', // <- idagdag ito
    ];
    
    // app/Models/Barangay.php
public function municipality()
{
    return $this->belongsTo(Municipality::class, 'municipality_id', 'id');
}


    // Relationships
    public function residents()
    {
        return $this->hasMany(Resident::class);
    }

    // Accessor for logo URL (optional)
    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }
    public function officials()
    {
        return $this->hasMany(User::class);
    }
    // Barangay.php
public function documents()
{
    return $this->hasMany(BarangayDocument::class);
}

// BarangayDocument.php
public function barangay()
{
    return $this->belongsTo(Barangay::class);
}

public function purposes()
{
    return $this->hasMany(BarangayDocumentPurpose::class);
}

// BarangayDocumentPurpose.php
public function document()
{
    return $this->belongsTo(BarangayDocument::class);
}
// BarangayOfficials relationship
public function barangayOfficials()
{
    return $this->hasMany(BarangayOfficial::class);
}

// System users relationship (Captain, Secretary, etc.)
public function users()
{
    return $this->hasMany(User::class);
}
public function announcements()
{
    return $this->hasMany(Announcement::class);
}
public function events()
{
    return $this->hasMany(Event::class);
}
// App/Models/Barangay.php

// App/Models/Barangay.php
public function proposals()
{
    return $this->hasMany(Proposal::class, 'barangay', 'id');
}


}
