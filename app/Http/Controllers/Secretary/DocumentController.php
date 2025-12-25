<?php
namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Resident;
use App\Models\Barangay;
use App\Models\BarangayOfficial;
use Carbon\Carbon;
use Auth;

class DocumentController extends Controller
{
    public function showClearanceForm($residentId)
    {
        $resident = Resident::findOrFail($residentId);
        $barangay = $resident->barangay;
        $captain = $barangay->officials()->where('position', 'Punong Barangay')->latest()->first();

        return view('secretary.documents.clearance', compact('resident', 'barangay', 'captain'));
    }
}
