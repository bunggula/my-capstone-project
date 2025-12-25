<?php

namespace App\Http\Controllers\Captain;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;  // Auth facade
use App\Models\BarangayOfficial;      // BarangayOfficial model
use App\Models\User; 
class NewController extends Controller
{
    public function officialsIndex(Request $request)
{
    $barangayId = Auth::user()->barangay_id;
    $yearNow = now()->year;

    // Optional position filter
    $positionFilter = $request->query('position');

    // Allowed positions
    $allowedPositions = ['Kagawad', 'SK Chairman'];

       // Paginate officials with optional filter (filter by valid term)
$officialsQuery = BarangayOfficial::with('user')
            ->where('barangay_id', $barangayId)
            ->where('start_year', '<=', $yearNow)
            ->where('end_year', '>=', $yearNow)
            ->orderBy('position');

    
        if ($positionFilter) {
            $officialsQuery->where('position', $positionFilter);
        }

    $officials = $officialsQuery->paginate()->withQueryString();

    // Current captain (active only)
    $captain = User::where('role', 'brgy_captain')
        ->where('barangay_id', $barangayId)
        ->where('status', 'active')
        ->first();

    // Current secretary (active only)
    $secretary = User::where('role', 'secretary')
        ->where('barangay_id', $barangayId)
        ->where('status', 'active')
        ->first();

    return view('captain.officials.index', compact(
        'officials',
        'captain',
        'secretary',
        'positionFilter'
    ));
}

}
