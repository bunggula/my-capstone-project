<?php

namespace App\Http\Controllers\ABC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Concern;
use App\Models\Barangay; // import barangay model

class AbcConcernController extends Controller
{
    public function index(Request $request)
{
$barangays = Barangay::all();


// ✅ Base query (resolved only)
$concernsQuery = Concern::with(['resident', 'barangay'])
    ->where('status', 'resolved')
    ->orderBy('created_at', 'desc');
// 🏘️ Filter by Barangay
if ($request->filled('barangay')) {
    $concernsQuery->where('barangay_id', $request->barangay);
}

// 🗓 Filter by Month
if ($request->filled('month')) {
    $concernsQuery->whereMonth('created_at', $request->month);
}

// 📅 Filter by Year
if ($request->filled('year')) {
    $concernsQuery->whereYear('created_at', $request->year);
}

// ✅ Total of ALL resolved (lahat ng barangay)
$totalResolved = Concern::where('status', 'resolved')->count();

// ✅ Total of filtered resolved (with barangay/month/year)
$filteredCount = (clone $concernsQuery)->count();

// 🧾 Paginated results
$concerns = $concernsQuery->paginate(5)->withQueryString();

return view('abc.concerns.index', compact(
    'concerns', 'barangays', 'totalResolved', 'filteredCount'
));
}

}
