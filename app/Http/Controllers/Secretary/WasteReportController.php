<?php

namespace App\Http\Controllers\Secretary;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\WasteReport;
use App\Models\User;
use Carbon\Carbon;

class WasteReportController extends Controller
{
    /**
     * Display all waste reports for the logged-in secretary's barangay.
     */
public function index(Request $request)
{
    // Base query filtered by user's barangay
    $query = WasteReport::where('barangay_id', auth()->user()->barangay_id);

    // Get report type from request, default to 'daily'
    $type = $request->input('type', 'daily');

    // Get pagination per page, default 10
    $perPage = $request->input('perPage', 10);

    // Validate type and required fields
    if ($type === 'daily' && !$request->filled('date')) {
        $type = 'daily'; // keep daily, but no date filter
    } elseif ($type === 'weekly' && (!$request->filled('start_date') || !$request->filled('end_date'))) {
        $type = 'daily'; // fallback to daily if weekly dates missing
    } elseif ($type === 'monthly' && !$request->filled('month')) {
        $type = 'daily'; // fallback to daily if month missing
    }

    // Apply filters only if fields are filled
    if ($type === 'daily' && $request->filled('date')) {
        $query->whereDate('date_collected', $request->date);
    } elseif ($type === 'weekly' && $request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('date_collected', [$request->start_date, $request->end_date]);
    } elseif ($type === 'monthly' && $request->filled('month')) {
        $year = substr($request->month, 0, 4);
        $month = substr($request->month, 5, 2);
        $query->whereYear('date_collected', $year)
              ->whereMonth('date_collected', $month);
    }

    // Get paginated results, preserve query string
    $wasteReports = $query->latest()->paginate($perPage)->withQueryString();

    // Pass $type explicitly to view so Alpine.js can read it correctly
    return view('secretary.reports.index', compact('wasteReports', 'type', 'perPage'));
}



    /**
     * Store a new waste report.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date_collected' => 'required|date',
            'biodegradable' => 'required|integer|min:0',
            'recyclable' => 'required|integer|min:0',
            'residual_recyclable' => 'required|integer|min:0',
            'residual_disposal' => 'required|integer|min:0',
            'special' => 'required|integer|min:0',
            'remarks' => 'nullable|string|max:255',
        ]);

        WasteReport::create([
            'barangay_id' => auth()->user()->barangay_id,
            'date_collected' => $request->date_collected,
            'biodegradable' => $request->biodegradable,
            'recyclable' => $request->recyclable,
            'residual_recyclable' => $request->residual_recyclable,
            'residual_disposal' => $request->residual_disposal,
            'special' => $request->special,
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('secretary.reports.index')
            ->with('success', 'Waste report added successfully!');
    }

    /**
     * Update an existing waste report.
     */
    public function update(Request $request, $id)
    {
        $report = WasteReport::findOrFail($id);

        $validated = $request->validate([
            'date_collected' => 'required|date',
            'biodegradable' => 'required|integer|min:0',
            'recyclable' => 'required|integer|min:0',
            'residual_recyclable' => 'required|integer|min:0',
            'residual_disposal' => 'required|integer|min:0',
            'special' => 'required|integer|min:0',
            'remarks' => 'nullable|string|max:255',
        ]);

        $report->update($validated);

        return redirect()->route('secretary.reports.index')
            ->with('success', 'Waste report updated successfully!');
    }

    /**
     * Generate waste report (daily, weekly, monthly)
     */
    public function generate(Request $request)
    {
        $type = $request->input('type');
        $reports = collect();

        if ($type === 'daily') {
            $date = $request->input('date');
            $reports = WasteReport::whereDate('date_collected', $date)->get();
        } elseif ($type === 'weekly') {
            $start = $request->input('start_date');
            $end = $request->input('end_date');
            $reports = WasteReport::whereBetween('date_collected', [$start, $end])->get();
        } elseif ($type === 'monthly') {
            $month = $request->input('month'); // format: YYYY-MM
            $reports = WasteReport::whereYear('date_collected', substr($month, 0, 4))
                ->whereMonth('date_collected', substr($month, 5, 2))
                ->get();
        }

        return view('secretary.reports.generate', compact('reports', 'type'));
    }

    /**
     * Print the waste report with barangay and official details.
     */
  public function print(Request $request)
{
    $type = $request->input('type');
    $reports = collect();
    $user = auth()->user();

    // 🔹 Get barangay and municipality
    $barangayModel = $user->barangay; // Make sure User has barangay() relationship
    $barangay = optional($barangayModel)->name ?? '__________';
    $municipalityName = optional($barangayModel?->municipality)->name ?? '__________';

    // 🔹 Get officials
    $captain = User::where('barangay_id', $user->barangay_id)
        ->where('role', 'brgy_captain')
        ->first();
    $secretary = User::where('barangay_id', $user->barangay_id)
        ->where('role', 'secretary')
        ->first();

    // 🔹 Fetch reports based on type
    if ($type === 'daily') {
        $date = $request->input('date');
        $reports = WasteReport::whereDate('date_collected', $date)
            ->orderBy('date_collected', 'asc')
            ->get();
        $monthYear = Carbon::parse($date)->format('F Y');
    } elseif ($type === 'weekly') {
        $start = $request->input('start_date');
        $end = $request->input('end_date');
        $reports = WasteReport::whereBetween('date_collected', [$start, $end])
            ->orderBy('date_collected', 'asc')
            ->get();
        $monthYear = Carbon::parse($start)->format('F Y');
    } elseif ($type === 'monthly') {
        $month = $request->input('month');
        $reports = WasteReport::whereYear('date_collected', substr($month, 0, 4))
            ->whereMonth('date_collected', substr($month, 5, 2))
            ->orderBy('date_collected', 'asc')
            ->get();
        $monthYear = Carbon::parse($month . '-01')->format('F Y');
    } else {
        $monthYear = '';
    }

    // 🔹 Return view with dynamic municipality
    return view('secretary.reports.print', [
        'reports'         => $reports,
        'type'            => $type,
        'barangay'        => $barangay,
        'municipalityName' => $municipalityName,
        'secretaryName'   => optional($secretary)->full_name ?? '__________',
        'captainName'     => optional($captain)->full_name ?? '__________',
        'monthYear'       => $monthYear,
    ]);
}


    public function viewAll()
    {
        // Fetch all waste reports (paginated)
        $wasteReports = WasteReport::orderBy('date_collected', 'desc')->paginate(15);
    
        // Return a dedicated view (we’ll make this next)
        return view('secretary.reports.view-all', compact('wasteReports'));
    }
    


}
