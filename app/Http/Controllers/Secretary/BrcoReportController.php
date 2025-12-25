<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use App\Models\BrcoReport;
use Illuminate\Http\Request;
use App\Models\User;
class BrcoReportController extends Controller
{
    // Display all BRCO reports (filter by barangay ng naka-login na secretary)
  public function index(Request $request)
{
    $user = auth()->user();

    $query = BrcoReport::where('barangay_id', $user->barangay_id);

    // Sanitize and validate date inputs manually for GET requests
    $fromDate = $request->input('from_date');
    $toDate = $request->input('to_date');
    $today = now()->toDateString();

    if ($fromDate && $fromDate <= $today) {
        $query->whereDate('date', '>=', $fromDate);
    }

    if ($toDate && $toDate <= $today) {
        $query->whereDate('date', '<=', $toDate);
    }

    $reports = $query->latest()->paginate(10)->withQueryString();

    return view('secretary.brco.index', compact('reports'));
}

    
    // Store new BRCO report
    public function store(Request $request)
    {
        $validated = $request->validate([
            'location'     => 'required|string',
            'length'       => 'required|string',
            'date'         => 'required|date|before_or_equal:today',
            'action_taken' => 'required|string',
            'remarks'      => 'nullable|string',
            'conducted'    => 'required|boolean',
            'photos.*'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'photo_captions.*' => 'nullable|string|max:255',
        ]);
    
        $report = BrcoReport::create([
            'location'     => $validated['location'],
            'length'       => $validated['length'],
            'date'         => $validated['date'],
            'action_taken' => $validated['action_taken'],
            'remarks'      => $validated['remarks'] ?? null,
            'conducted'    => $validated['conducted'],
            'user_id'      => auth()->id(),
            'barangay_id'  => auth()->user()->barangay_id, // 🔑 ito yung kulang
        ]);
    
        if ($request->hasFile('photos')) {
            $photos = $request->file('photos');
            $captions = $request->input('photo_captions', []); // array ng captions
        
            foreach ($photos as $index => $photo) {
                $path = $photo->store('brco_photos', 'public');
                $caption = $captions[$index] ?? null; // kunin ang caption kung meron
        
                $report->photos()->create([
                    'path' => $path,
                    'caption' => $caption,
                ]);
            }
        }
        
        return redirect()->route('secretary.brco.index')
                         ->with('success', 'BRCO Report created successfully.');
    }
    public function show($id)
    {
        $report = BrcoReport::with('photos')->findOrFail($id);
        return response()->json($report);
    }
    public function create()
    {
        $reports = \App\Models\BrcoReport::all(); // lahat ng reports lang
        return view('secretary.brco.create', compact('reports'));
    }
    public function update(Request $request, BrcoReport $brco)
{
    $validated = $request->validate([
        'location'     => 'required|string',
        'length'       => 'required|string',
        'date'         => 'required|date',
        'action_taken' => 'required|string',
        'remarks'      => 'nullable|string',
        'conducted'    => 'required|boolean',
        'photos.*'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'photo_captions.*' => 'nullable|string|max:255',
        'delete_photos.*' => 'nullable|boolean',
    ]);

    // Update main report
    $brco->update([
        'location'     => $validated['location'],
        'length'       => $validated['length'],
        'date'         => $validated['date'],
        'action_taken' => $validated['action_taken'],
        'remarks'      => $validated['remarks'] ?? null,
        'conducted'    => $validated['conducted'],
    ]);

    // Delete selected photos
    if ($request->filled('delete_photos')) {
        foreach ($request->delete_photos as $photoId => $val) {
            $photo = $brco->photos()->find($photoId);
            if ($photo) {
                \Storage::disk('public')->delete($photo->path);
                $photo->delete();
            }
        }
    }

    // Update existing photo captions
    if ($request->filled('photo_captions')) {
        foreach ($request->photo_captions as $photoId => $caption) {
            $photo = $brco->photos()->find($photoId);
            if ($photo) {
                $photo->update(['caption' => $caption]);
            }
        }
    }

    // Upload new photos
    if ($request->hasFile('photos')) {
        foreach ($request->file('photos') as $photoFile) {
            $path = $photoFile->store('brco_photos', 'public');
            $brco->photos()->create([
                'path' => $path,
                'caption' => null, // Optionally, you can allow input for new captions too
            ]);
        }
    }

    return redirect()->route('secretary.brco.index')
                     ->with('success', 'BRCO Report updated successfully.');
}
public function print(Request $request)
{
    $user = auth()->user();

    $query = BrcoReport::where('barangay_id', $user->barangay_id);

    if ($request->filled('from_date') && $request->filled('to_date')) {
        $query->whereBetween('date', [$request->from_date, $request->to_date]);
    }

    $reports = $query->latest()->get();

    // Get secretary (current logged-in user)
    $secretaryName = trim($user->first_name.' '.$user->middle_name.' '.$user->last_name);

    // Get captain for the same barangay
    $captain = User::where('barangay_id', $user->barangay_id)
                   ->where('role', 'brgy_captain')
                   ->first();

    $captainName = $captain 
                   ? trim($captain->first_name.' '.$captain->middle_name.' '.$captain->last_name)
                   : 'N/A';

    return view('secretary.brco.print', compact('reports', 'secretaryName', 'captainName', 'user'));
}


}
