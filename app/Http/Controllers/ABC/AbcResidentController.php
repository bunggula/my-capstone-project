<?php

namespace App\Http\Controllers\ABC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Resident;
use App\Models\Barangay;
use Barryvdh\DomPDF\Facade\Pdf;

class AbcResidentController extends Controller
{
    public function index(Request $request)
    {
        
        $barangays = Barangay::all();
        $selectedBarangay = $request->input('barangay');
        $search   = $request->input('search');
        $status   = $request->input('status', 'approved');
        $filter   = $request->input('filter'); 
        $category = $request->input('category'); // PWD, Senior, Indigenous, Single Parent, Minor, Adult
        $voter    = $request->input('voter');    // Yes / No
    
        // 🔹 Base query for residents list
        $residents = Resident::query()
            ->when($selectedBarangay, fn ($q) =>
                $q->where('barangay_id', $selectedBarangay))
            ->when($search, fn ($q) =>
                $q->where(function ($query) use ($search) {
                    $query->where('first_name', 'like', "%$search%")
                          ->orWhere('last_name', 'like', "%$search%")
                          ->orWhere('email', 'like', "%$search%")
                          ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"])
                          ->orWhereRaw("CONCAT(first_name, ' ', middle_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                }))
            ->when($status, fn ($q) =>
                $q->where('status', $status))
            ->when($filter === 'voters', fn($q) =>
                $q->where('voter', 'Yes'))
            ->when($filter === 'male', fn($q) =>
                $q->where('gender', 'male'))
            ->when($filter === 'female', fn($q) =>
                $q->where('gender', 'female'))
            ->when($filter === 'minors', fn($q) =>
                $q->where('age', '<', 18))
            ->when($filter === 'adults', fn($q) =>
                $q->where('age', '>=', 18))
            // 🆕 Category filter (supports multiple values in field)
            ->when($category, function ($q) use ($category) {
                if (in_array($category, ['Minor','Adult'])) {
                    if ($category === 'Minor') {
                        $q->where('age', '<', 18);
                    } else {
                        $q->where('age', '>=', 18);
                    }
                } else {
                    $q->whereRaw("FIND_IN_SET(?, category)", [$category]);
                }
            })
            // 🆕 Voter filter
            ->when($voter, fn($q) =>
                $q->where('voter', $voter))
            ->with('barangay')
            ->orderBy('first_name')
            ->paginate(5)
            ->withQueryString();
    
        // 🔹 Base query for stats (same filters as residents)
        $baseStats = Resident::when($selectedBarangay, fn ($q) =>
                            $q->where('barangay_id', $selectedBarangay))
                        ->where('status', 'approved');
    
        if ($category) {
            if (in_array($category, ['Minor','Adult'])) {
                if ($category === 'Minor') {
                    $baseStats->where('age', '<', 18);
                } else {
                    $baseStats->where('age', '>=', 18);
                }
            } else {
                $baseStats->whereRaw("FIND_IN_SET(?, category)", [$category]);
            }
        }
    
        if ($voter) {
            $baseStats->where('voter', $voter);
        }
    
        if ($search) {
            $baseStats->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                  ->orWhere('last_name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"])
                  ->orWhereRaw("CONCAT(first_name, ' ', middle_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
            });
        }
    
        // 🔹 Stats counts (search + filters respected)
        $stats = [
            'all'          => (clone $baseStats)->count(),
            'voters'       => (clone $baseStats)->where('voter', 'Yes')->count(),
            'no_voter'     => (clone $baseStats)->where('voter', 'No')->count(),
            'male'         => (clone $baseStats)->where('gender', 'male')->count(),
            'female'       => (clone $baseStats)->where('gender', 'female')->count(),
            'minors'       => (clone $baseStats)->where('age', '<', 18)->count(),
            'adults'       => (clone $baseStats)->where('age', '>=', 18)->count(),
            'pwd'          => (clone $baseStats)->whereRaw("FIND_IN_SET('PWD', category)")->count(),
            'senior'       => (clone $baseStats)->whereRaw("FIND_IN_SET('Senior', category)")->count(),
            'indigenous'   => (clone $baseStats)->whereRaw("FIND_IN_SET('Indigenous', category)")->count(),
            'single_parent'=> (clone $baseStats)->whereRaw("FIND_IN_SET('Single Parent', category)")->count(),
        ];
    
        return view('abc.residents.index', compact(
            'residents', 'barangays', 'selectedBarangay',
            'stats', 'category', 'voter', 'search'
        ));
    }
    


    public function exportPDF(Request $request)
    {
        // ✅ Only get approved residents for PDF
        $query = Resident::with('barangay')->where('status', 'approved'); // ✅ Correct

        if ($request->filled('barangay')) {
            $query->where('barangay_id', $request->barangay);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%")
                    ->orWhere('middle_name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%");
            });
        }

        $residents = $query->orderBy('last_name')->get();

        $pdf = Pdf::loadView('exports.residents-pdf', compact('residents'));

        return $pdf->download('municipality_residents.pdf');
    }

    public function create()
    {
        $barangays = Barangay::all();
        return view('abc.residents.create', compact('barangays'));
    }

    public function store(Request $request)
{
    $validatedData = $request->validate([
        'first_name' => 'required|string|max:100',
        'middle_name' => 'nullable|string|max:100',
        'last_name' => 'required|string|max:100',
        'suffix' => 'nullable|string|max:20',
        'birthdate' => 'required|date',
        'gender' => 'required|in:male,female,other',
        'civil_status' => 'required|string',
        'category' => 'nullable|string', // sent as CSV string from form
        'email' => 'nullable|email',
        'phone' => 'nullable|string|max:20',
        'barangay_id' => 'required|exists:barangays,id',
        'address' => 'required|string|max:255',
    ]);

    // Add computed age
    $validatedData['age'] = \Carbon\Carbon::parse($validatedData['birthdate'])->age;

    // ✅ Auto-approved
    $validatedData['status'] = 'approved';

    Resident::create($validatedData);
    return redirect()->route('abc.residents.index')->with('success', 'Resident added successfully.');

}

    public function show($id)
    {
        $resident = Resident::with('barangay')->findOrFail($id);
        return view('abc.residents.show', compact('resident'));
    }
    public function print(Request $request)
    {
        $query = Resident::query()
            ->with('barangay')
            ->where('status', 'Approved'); // ✅ Only approved residents
    
        // ✅ Apply filters
        if ($request->barangay) {
            $query->where('barangay_id', $request->barangay);
        }
    
        if ($request->category) {
            // ✅ Handle multiple comma-separated categories (e.g. "PWD,Senior")
            $query->where('category', 'like', "%{$request->category}%");
        }
    
        if ($request->voter) {
            $query->where('voter', $request->voter);
        }
    
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->search}%")
                  ->orWhere('last_name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }
    
        $residents = $query->orderBy('first_name')->get();
    
        // ✅ Get barangay name for header
        $barangayName = optional(Barangay::find($request->barangay))->name ?? 'All Barangays';
    
        // ✅ Get the ABC Admin user dynamically (for Prepared By)
        $abcAdmin = \App\Models\User::where('role', 'abc_admin')->first();
    
        return view('abc.residents.print', compact('residents', 'barangayName', 'request', 'abcAdmin'));
    }
    
}
