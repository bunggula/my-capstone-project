<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use App\Models\BarangayOfficial;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfficialController extends Controller
{

    public function index(Request $request)
    {
        $barangayId = Auth::user()->barangay_id;
        $yearNow = now()->year;
    
        // Get the filter from query params
        $positionFilter = $request->query('position');
    
        // Paginate officials with optional filter (filter by valid term)
        $officialsQuery = BarangayOfficial::with('user')
            ->where('barangay_id', $barangayId)
            ->where('start_year', '<=', $yearNow)
            ->where('end_year', '>=', $yearNow)
            ->orderBy('position');
    
        if ($positionFilter) {
            $officialsQuery->where('position', $positionFilter);
        }
    
        $officials = $officialsQuery->paginate(5)->withQueryString();
    
        // ✅ Captain (active only)
        $captain = User::where('role', 'brgy_captain')
                       ->where('barangay_id', $barangayId)
                       ->where('status', 'active')
                       ->first();
    
        // ✅ Secretary (active only)
        $secretary = User::where('role', 'secretary')
                         ->where('barangay_id', $barangayId)
                         ->where('status', 'active')
                         ->first();
    
        return view('secretary.officials.index', compact(
            'officials',
            'captain',
            'secretary',
            'positionFilter'
        ));
    }
    

    public function create()
    {
        return view('secretary.officials.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'   => 'required|string|max:255',
            'middle_name'  => 'nullable|string|max:255',
            'last_name'    => 'required|string|max:255',
            'suffix'       => 'nullable|string|max:10',
            'gender'       => 'required|in:Male,Female',
            'birthday'     => 'required|date',
            'civil_status' => 'required|in:Single,Married,Widow,Separated',
            'email'        => 'nullable|email',
            'phone'        => 'nullable|digits:11',
            'position'     => 'required|in:Kagawad,Vaw Desk Officer,Barangay Tanod,SK Chairman',
            'start_year'   => 'required|integer|min:2000|max:' . (date('Y') + 10),
            'end_year'     => 'required|integer|min:2000|max:' . (date('Y') + 10),
            'categories'   => 'nullable|array',
            'categories.*' => 'string|in:PWD,Senior,Indigenous,Solo Parent',
        ]);
    
        // Save categories as array (not JSON)
        $validated['categories'] = $request->input('categories', []);
        $validated['barangay_id'] = Auth::user()->barangay_id;
        $validated['age'] = \Carbon\Carbon::parse($validated['birthday'])->age;
    
        BarangayOfficial::create($validated);
    
        return redirect()->route('secretary.officials.index')
                         ->with('success', 'Official added successfully.');
    }
    
    
    public function update(Request $request, $id)
    {
        // Basic validation for all fields
        $validated = $request->validate([
            'first_name'   => 'required|string|max:255',
            'middle_name'  => 'nullable|string|max:255',
            'last_name'    => 'required|string|max:255',
            'suffix'       => 'nullable|string|max:10',
            'gender'       => 'required|in:Male,Female',
            'birthday'     => 'required|date',
            'civil_status' => 'required|in:Single,Married,Widow,Separated',
            'email'        => 'nullable|email',
            'phone'        => 'nullable|digits:11',
            'position'     => 'required|in:Kagawad,Vaw Desk Officer,Barangay Tanod,SK Chairman',
            'categories'   => 'nullable|array',
            'categories.*' => 'string|in:PWD,Senior,Indigenous,Solo Parent',
        ]);
    
        // Only update start/end year if present in request
        if ($request->filled('start_year') && $request->filled('end_year')) {
            $validated['start_year'] = $request->start_year;
            $validated['end_year'] = $request->end_year;
        }
    
        // Categories
        $validated['categories'] = $request->input('categories', []);
    
        // Age
        $validated['age'] = \Carbon\Carbon::parse($validated['birthday'])->age;
    
        $official = BarangayOfficial::findOrFail($id);
        $official->update($validated);
    
        return redirect()->back()->with('success', 'Official updated successfully!');
    }
    
    
}

