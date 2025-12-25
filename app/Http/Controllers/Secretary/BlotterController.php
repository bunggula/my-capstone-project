<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blotter;
use App\Models\BlotterComplainant;
use App\Models\BlotterRespondent;
use App\Models\Resident;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
class BlotterController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $search = $request->query('search'); 
        $month  = $request->query('month');  
        $year   = $request->query('year');   
        $status = $request->query('status'); 
    
        $query = Blotter::where('barangay_id', $user->barangay_id)
            ->with(['complainants', 'respondents'])
            ->orderByDesc('created_at');
    
        if ($status) $query->where('status', $status);
        if ($search) { 
            $query->where(function ($q) use ($search) {
                $q->whereHas('complainants', fn($sub) =>
                    $sub->where('first_name','like',"%{$search}%")
                        ->orWhere('middle_name','like',"%{$search}%")
                        ->orWhere('last_name','like',"%{$search}%")
                )->orWhereHas('respondents', fn($sub) =>
                    $sub->where('first_name','like',"%{$search}%")
                        ->orWhere('middle_name','like',"%{$search}%")
                        ->orWhere('last_name','like',"%{$search}%")
                );
            });
        }
        if ($month) $query->whereMonth('created_at', $month);
        if ($year) $query->whereYear('created_at', $year);
    
       
$totalBlotters = $query->count();
$blotters = $query->paginate(10)->withQueryString();

// Only approved residents from same barangay, sorted alphabetically
$residents = Resident::where('barangay_id', $user->barangay_id)
    ->where('status', 'approved')
    ->orderBy('first_name')
    ->orderBy('last_name')
    ->get();

return view('secretary.blotter.index', compact(
    'blotters',
    'search',
    'month',
    'year',
    'status',
    'totalBlotters',
    'residents'
));
    }
    



    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'description' => 'required|string',
            'complainants.*.first_name' => 'required|string|max:255',
            'complainants.*.last_name' => 'required|string|max:255',
           'respondents.*.first_name' => 'nullable|string|max:255',
'respondents.*.last_name' => 'nullable|string|max:255',

        ]);

        $user = auth()->user();

        $blotter = Blotter::create([
            'barangay_id' => $user->barangay_id,
            'date' => $request->date,
            'time' => $request->time,
            'description' => $request->description,
        ]);

        foreach ($request->complainants as $complainant) {
            BlotterComplainant::create([
                'blotter_id'   => $blotter->id,
                'resident_id'  => $complainant['resident_id'] ?? null, // New
                'first_name'   => $complainant['first_name'] ?? null,
                'middle_name'  => $complainant['middle_name'] ?? null,
                'last_name'    => $complainant['last_name'] ?? null,
            ]);
        }
        
        if($request->has('respondents')){
            foreach ($request->respondents as $respondent) {
                if(!empty($respondent['first_name']) || !empty($respondent['last_name']) || !empty($respondent['resident_id'])){
                    BlotterRespondent::create([
                        'blotter_id'  => $blotter->id,
                        'resident_id' => $respondent['resident_id'] ?? null, // New
                        'first_name'  => $respondent['first_name'] ?? null,
                        'middle_name' => $respondent['middle_name'] ?? null,
                        'last_name'   => $respondent['last_name'] ?? null,
                    ]);
                }
            }
        }
        

        return back()->with('success', 'Blotter record added successfully!');
    }

    // =======================
    // VIEW DETAILS (AJAX)
    // =======================
    public function show($id)
    {
        $blotter = Blotter::with(['complainants.resident', 'respondents.resident'])->findOrFail($id);
    
        // Attach count per resident
        foreach ($blotter->complainants as $c) {
            if($c->resident_id){
                $c->total_as_complainant = BlotterComplainant::where('resident_id', $c->resident_id)->count();
                $c->total_as_respondent = BlotterRespondent::where('resident_id', $c->resident_id)->count();
            } else {
                $c->total_as_complainant = 0;
                $c->total_as_respondent = 0;
            }
        }
    
        foreach ($blotter->respondents as $r) {
            if($r->resident_id){
                $r->total_as_complainant = BlotterComplainant::where('resident_id', $r->resident_id)->count();
                $r->total_as_respondent = BlotterRespondent::where('resident_id', $r->resident_id)->count();
            } else {
                $r->total_as_complainant = 0;
                $r->total_as_respondent = 0;
            }
        }
    
        return response()->json($blotter);
    }
    

    // =======================
    // EDIT DATA (AJAX)
    // =======================
    public function edit($id)
    {
        $blotter = Blotter::with(['complainants', 'respondents'])->findOrFail($id);
        return response()->json($blotter);
    }

    // =======================
    // UPDATE
    // =======================
    public function update(Request $request, $id)
    {
        // Validation
        $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'description' => 'required|string',
            'complainants.*.first_name' => 'required|string|max:255',
            'complainants.*.last_name' => 'required|string|max:255',
            'respondents.*.first_name' => 'required|string|max:255',
            'respondents.*.last_name' => 'required|string|max:255',
            'signed_file' => 'nullable|file|mimes:pdf,jpg,png|max:2048', // optional signed file
        ]);
    
        $blotter = Blotter::findOrFail($id);
    
        // Update main blotter info
        $blotter->update([
            'date' => $request->date,
            'time' => $request->time,
            'description' => $request->description,
        ]);
    
        // Update complainants
        $blotter->complainants()->delete();
        foreach ($request->complainants as $complainant) {
            $blotter->complainants()->create([
                'resident_id' => $complainant['resident_id'] ?? null,
                'first_name'  => $complainant['first_name'] ?? null,
                'middle_name' => $complainant['middle_name'] ?? null,
                'last_name'   => $complainant['last_name'] ?? null,
            ]);
        }
    
        // Update respondents
        $blotter->respondents()->delete();
        if($request->has('respondents')){
            foreach ($request->respondents as $respondent) {
                if(!empty($respondent['first_name']) || !empty($respondent['last_name']) || !empty($respondent['resident_id'])){
                    $blotter->respondents()->create([
                        'resident_id' => $respondent['resident_id'] ?? null,
                        'first_name'  => $respondent['first_name'] ?? null,
                        'middle_name' => $respondent['middle_name'] ?? null,
                        'last_name'   => $respondent['last_name'] ?? null,
                    ]);
                }
            }
        }
    
        // Handle optional signed file upload
        if ($request->hasFile('signed_file')) {
            // Delete old file if exists
            if ($blotter->signed_file && \Storage::disk('public')->exists($blotter->signed_file)) {
                \Storage::disk('public')->delete($blotter->signed_file);
            }
        
            // Store new file
            $file = $request->file('signed_file')->store('blotters/signed_files', 'public');
            $blotter->signed_file = $file;
            $blotter->is_signed = true;
            $blotter->signed_at = now();
            $blotter->save();
        }
        
    
        return back()->with('success', 'Blotter record updated successfully!');
    }
    
    // =======================
    // PRINT (optional)
    // =======================
    public function print($id)
    {
        $blotter = Blotter::with(['complainants', 'respondents'])->findOrFail($id);
    
        $captain = Auth::user(); // naka-login na user
        $barangayName = $captain->barangay_id ? $captain->barangay->name ?? 'N/A' : 'N/A';
    
        // Format ng date at time
        $formattedDateTime = \Carbon\Carbon::parse($blotter->date.' '.$blotter->time)
            ->format('F j, Y g:i A'); // Example: October 12, 2025 9:00 AM
    
        return view('secretary.blotter.print', compact('blotter','barangayName','formattedDateTime','captain'));
    }
    public function uploadSigned(Request $request, $id)
    {
        $request->validate([
            'signed_file' => 'required|file|mimes:pdf,jpg,png|max:2048', // limit 2MB
        ]);
    
        $blotter = Blotter::findOrFail($id);
    
        // Store file sa public disk -> storage/app/public/blotters/signed_files
        $file = $request->file('signed_file')->store('blotters/signed_files', 'public');
    
        $blotter->signed_file = $file;
        $blotter->is_signed = true;
        $blotter->signed_at = now();
        $blotter->save();
    
        return back()->with('success', 'Signed blotter uploaded successfully.');
    }
}    
