<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Resident;
use App\Models\Barangay;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResidentCredentialsMail;
use App\Models\BarangayDocument;

class ResidentController extends Controller
{
    public function create()
    {
        return view('secretary.residents.create');
    }

  public function store(Request $request)
{
    // ✅ Validate input
    $validated = $request->validate([
        'first_name'    => 'required|string|max:255',
        'middle_name'   => 'nullable|string|max:255',
        'last_name'     => 'required|string|max:255',
        'suffix'        => 'nullable|string|max:10',
        'gender'        => 'required|in:Male,Female',
        'birthdate'     => 'required|date',
        'civil_status'  => 'required|string|max:50',
        'categories'    => 'nullable|array',
        'categories.*'  => 'string|in:PWD,Senior,Indigenous,Single Parent',
        'email'         => 'nullable|email|max:255',
        'phone'         => 'nullable|string|size:11', // optional for walk-in
        'zone'          => 'required|integer|min:1',
        'voter'         => 'required|in:Yes,No',
    ]);

    // ✅ Calculate age and assign default info
    $validated['age'] = Carbon::parse($validated['birthdate'])->age;
    $validated['barangay_id'] = Auth::user()->barangay_id;
    $validated['status'] = 'approved';

    // ✅ Handle categories
    $categories = $validated['categories'] ?? [];
    if (!is_array($categories)) $categories = explode(',', $categories);
    $validated['category'] = implode(',', $categories);
    unset($validated['categories']);

    // 🔍 Duplicate check (same barangay)
    $barangayDuplicate = Resident::where('barangay_id', $validated['barangay_id'])
        ->where('first_name', $validated['first_name'])
        ->where('middle_name', $validated['middle_name'])
        ->where('last_name', $validated['last_name'])
        ->where('suffix', $validated['suffix'])
        ->where('birthdate', $validated['birthdate'])
        ->first();

    if ($barangayDuplicate) {
        return back()->withInput()->withErrors([
            'duplicate' => 'This resident already exists in your barangay.'
        ]);
    }

    // 🔍 Duplicate check (other barangays)
    $systemDuplicate = Resident::with('barangay')
        ->where('first_name', $validated['first_name'])
        ->where('middle_name', $validated['middle_name'])
        ->where('last_name', $validated['last_name'])
        ->where('suffix', $validated['suffix'])
        ->where('birthdate', $validated['birthdate'])
        ->where('barangay_id', '!=', $validated['barangay_id'])
        ->first();

    if ($systemDuplicate) {
        return back()->withInput()->withErrors([
            'duplicate' => 'This resident already exists in another barangay (' 
                . $systemDuplicate->barangay->name . ').'
        ]);
    }

    // ✅ Auto-classify Walk-In vs Online
    $validated['type'] = empty($validated['phone']) ? 'walk-in' : 'online';

    // ✅ Create resident with temporary password
    $tempPassword = Str::random(8);
    $validated['password'] = bcrypt($tempPassword);
    $validated['must_change_password'] = true;

    $resident = Resident::create($validated);

    // ✅ Send email credentials only if email exists
    if (!empty($validated['email'])) {
        try {
            Mail::to($resident->email)->send(new ResidentCredentialsMail(
                $resident->first_name . ' ' . $resident->last_name,
                $resident->email,
                $tempPassword
            ));
        } catch (\Exception $e) {
            \Log::error("Failed to send email: {$e->getMessage()}");
        }
    }

    // ✅ Redirect with success message
    return redirect()->route('secretary.residents.index')->with('success', 'Resident added successfully.');
}


    
public function index(Request $request)
{
    $barangayId = auth()->user()->barangay_id;

    $status   = $request->input('status', 'approved'); // approved / archived
    $search   = $request->input('search');
    $category = $request->input('category');
    $voter    = $request->input('voter'); // Yes / No
    $gender   = $request->input('gender'); // Male / Female

    // 🔹 Base query para sa counts
    $countBase = Resident::where('barangay_id', $barangayId);

    if ($search) {
        $countBase->where(function($q) use ($search) {
            $q->whereRaw("CONCAT(first_name, ' ', middle_name, ' ', last_name) LIKE ?", ["%{$search}%"])
              ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"])
              ->orWhere('first_name', 'like', "%{$search}%")
              ->orWhere('middle_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%");
        });
    }

    if ($category && $category !== 'Archived') {
        if ($category === 'Minor') {
            $countBase->whereNotNull('birthdate')
                      ->whereRaw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) < 18');
        } elseif ($category === 'Adult') {
            $countBase->whereNotNull('birthdate')
                      ->whereRaw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) >= 18');
        } else {
            $countBase->where('category', 'like', "%{$category}%");
        }
    }

    if ($voter) {
        $countBase->where('voter', $voter);
    }

    // ✅ Counts array
    $counts = [
        'all'      => (clone $countBase)->where('status', 'approved')->count(),
        'archived' => (clone $countBase)->where('status', 'archived')->count(),
        'male'     => (clone $countBase)->where('status', 'approved')->where('gender', 'Male')->count(),
        'female'   => (clone $countBase)->where('status', 'approved')->where('gender', 'Female')->count(),
    ];

    // 🔹 Base query para sa table
    $query = Resident::where('barangay_id', $barangayId);

    // Status filter
    $query->where('status', $status === 'archived' ? 'archived' : 'approved');

    // Search filter (same as counts para gumana full name search)
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->whereRaw("CONCAT(first_name, ' ', middle_name, ' ', last_name) LIKE ?", ["%{$search}%"])
              ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"])
              ->orWhere('first_name', 'like', "%{$search}%")
              ->orWhere('middle_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%");
        });
    }

    // Category filter (ignore 'Archived' category)
    if ($category && $category !== 'Archived') {
        if ($category === 'Minor') {
            $query->whereNotNull('birthdate')
                  ->whereRaw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) < 18');
        } elseif ($category === 'Adult') {
            $query->whereNotNull('birthdate')
                  ->whereRaw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) >= 18');
        } else {
            $query->where('category', 'like', "%{$category}%");
        }
    }

    // Voter filter
    if ($voter) {
        $query->where('voter', $voter);
    }

    // Gender filter (card click)
    if ($gender) {
        $query->where('gender', $gender);
    }

    // Paginate results with query string
    $residents = $query->orderBy('first_name')->paginate(20)->withQueryString();
 $documents = BarangayDocument::where('barangay_id', auth()->user()->barangay_id)->get();
   return view('secretary.residents.index', compact(
        'residents', 'counts', 'status', 'search', 'category', 'voter', 'gender', 'documents'
    ));
}


    
    public function edit(Resident $resident)
    {
        $barangays = Barangay::all();
        return view('secretary.residents.edit', compact('resident', 'barangays'));
    }

    public function update(Request $request, Resident $resident)
    {
        $validated = $request->validate([
            'first_name'     => 'required|string|max:255',
            'middle_name'    => 'nullable|string|max:255',
            'last_name'      => 'required|string|max:255',
            'suffix'         => 'nullable|string|max:10',
            'gender'         => 'required|in:Male,Female',
            'birthdate'      => 'required|date',
            'civil_status'   => 'required|string|max:50',
            'categories'     => 'nullable|array',
            'categories.*'   => 'string|in:PWD,Senior,Indigenous,Single Parent',
            'email'          => 'nullable|email|max:255',
            'phone'          => 'nullable|string|max:20',
            'voter'          => 'required|in:Yes,No',
            'zone'           => 'required|integer|min:1',
        ]);
    
        // Calculate age
        $birthdate = new \DateTime($validated['birthdate']);
        $today = new \DateTime();
        $age = $today->diff($birthdate)->y;
    
        // Handle category checkboxes
        $categories = $request->input('categories', []);
    
        // Auto-add 'Senior' if age >= 60
        if ($age >= 60 && !in_array('Senior', $categories)) {
            $categories[] = 'Senior';
        }
    
        // Save categories as comma-separated string
        $validated['category'] = implode(',', $categories);
    
        // Remove the raw categories array before saving
        unset($validated['categories']);
    
        // Update resident
        $resident->update($validated);
    
        return redirect()->route('secretary.residents.index')->with('success', 'Resident updated successfully.');
    }
    

    public function show(Resident $resident)
    {
        return view('secretary.residents.show', compact('resident'));
    }
    public function print(Request $request)
    {
        $barangayId = Auth::user()->barangay_id;
    
        $query = Resident::with('barangay')
            ->where('barangay_id', $barangayId)
            ->where('status', 'Approved');
    
        // Handle category filter
        $selectedCategory = $request->category;
        if ($selectedCategory) {
            if (in_array($selectedCategory, ['Minor', 'Adult'])) {
                // Filter by age
                $cutoffDate = now()->subYears(18)->format('Y-m-d');
                if ($selectedCategory === 'Minor') {
                    $query->whereDate('birthdate', '>', $cutoffDate);
                } else { // Adult
                    $query->whereDate('birthdate', '<=', $cutoffDate);
                }
            } else {
                // Filter by standard category
                $query->where('category', 'like', "%{$selectedCategory}%");
            }
        }
    
        // Voter filter
        $selectedVoter = $request->voter;
        if ($selectedVoter) {
            $query->where('voter', $selectedVoter);
        }
    
        // Search filter
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->search}%")
                  ->orWhere('last_name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }
    
        $residents = $query->orderBy('first_name')->get();
    
        $barangayName = optional(Barangay::find($barangayId))->name ?? 'N/A';
    $barangay = Barangay::with('municipality')->find($barangayId);

        return view('secretary.residents.print', [
            'residents' => $residents,
            'barangay' => $barangay,
            'barangayName' => $barangayName,
            'selectedCategory' => $selectedCategory,
            'selectedVoter' => $selectedVoter,
        ]);
    }
    
    
    public function downloadPdf()
    {
        $barangayId = Auth::user()->barangay_id;
        $residents = Resident::with('barangay')->where('barangay_id', $barangayId)->get();
        $pdf = Pdf::loadView('secretary.residents.print', compact('residents'));

        return $pdf->download('residents_list.pdf');
    }

    public function viewModal($id)
    {
        $user = Auth::user();

        $resident = Resident::where('id', $id)
                            ->where('barangay_id', $user->barangay_id)
                            ->firstOrFail();

        return view('secretary.residents.view-modal', compact('resident'));
    }

    public function rejected(Request $request)
    {
        $barangayId = Auth::user()->barangay_id;

        $query = Resident::where('status', 'rejected')
                         ->where('barangay_id', $barangayId);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('middle_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $rejectedResidents = $query->orderBy('last_name')->get();

        return view('secretary.residents.rejected', compact('rejectedResidents'));
    }

    // (Optional) You can add this if you want to archive via status change instead of delete:
   // Archive
public function archive(Request $request, Resident $resident)
{
    $resident->update(['status' => 'archived']);
    return redirect()->route('secretary.residents.index')->with('success', 'Resident archived successfully.');
}

// Unarchive
public function unarchive(Request $request, Resident $resident)
{
    $resident->update(['status' => 'approved']); // or 'active' if you use that
    return redirect()->route('secretary.residents.index', ['status' => 'archived'])
                     ->with('success', 'Resident unarchived successfully.');
}

}
