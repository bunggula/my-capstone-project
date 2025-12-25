<?php

namespace App\Http\Controllers\Captain;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use Illuminate\Support\Facades\Auth;

class ProposalController extends Controller
{
public function index(Request $request)
{
    $status = $request->query('status', 'pending'); // current tab
    $search = $request->query('search');
    $month  = $request->query('month');
    $year   = $request->query('year');

    // 🔹 Base query para sa main table
    $query = Proposal::with('barangayInfo')
        ->where('captain_id', Auth::id())
        ->when($search, fn($q) => $q->where('title', 'like', "%{$search}%"))
        ->when($month, fn($q) => $q->whereMonth('created_at', $month))
        ->when($year, fn($q) => $q->whereYear('created_at', $year));

    // 🔹 Auto-switch tab lamang sa unang search
    if ($search && !$request->has('status')) {
        $firstMatch = (clone $query)->first();
        if ($firstMatch && $status !== $firstMatch->status) {
            return redirect()->route('captain.proposal.index', [
                'status' => $firstMatch->status,
                'search' => $search,
                'month'  => $month,
                'year'   => $year,
            ]);
        }
    }

    // 🔹 Status filter (skip if "all")
    if ($status !== 'all') {
        $query->where('status', $status);
    }

    // 🔹 Counts (reflecting search, month, year)
    $countQuery = Proposal::where('captain_id', Auth::id())
        ->when($search, fn($q) => $q->where('title', 'like', "%{$search}%"))
        ->when($month, fn($q) => $q->whereMonth('created_at', $month))
        ->when($year, fn($q) => $q->whereYear('created_at', $year));

    $counts = [
        'all'      => (clone $countQuery)->count(),
        'pending'  => (clone $countQuery)->where('status', 'pending')->count(),
        'approved' => (clone $countQuery)->where('status', 'approved')->count(),
        'rejected' => (clone $countQuery)->where('status', 'rejected')->count(),
    ];

    // 🔹 Paginate results with query string
    $proposals = $query->latest()->paginate(10)->withQueryString();

    // 🔹 Return view
    return view('captain.proposal.index', compact(
        'proposals', 
        'status', 
        'counts', 
        'search', 
        'month', 
        'year'
    ));
}

    
    public function create()
    {
        return view('captain.proposal.create');
    }

    public function store(Request $request)
{
    // ✅ Step 1: Validate incoming data
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'source_of_fund' => 'nullable|string|max:255',
        'target_date' => 'nullable|date',
        'image' => 'nullable|image|max:2048',
        'file' => 'nullable|mimes:pdf,doc,docx|max:5120',
        
    ]);

    // ✅ Step 2: Handle file uploads
    $imagePath = $request->file('image') ? $request->file('image')->store('proposals/images', 'public') : null;
    $filePath = $request->file('file') ? $request->file('file')->store('proposals/files', 'public') : null;

    // ✅ Step 3: Create the proposal
    Proposal::create([
        'captain_id' => Auth::id(),
       
        'barangay' => Auth::user()->barangay_id ?? 'Unknown',
        'title' => $validated['title'],
        'description' => $validated['description'] ?? null,
        'image' => $imagePath,
        'file' => $filePath,
        'status' => 'pending',
        'source_of_fund' => $validated['source_of_fund'] ?? null,
        'target_date' => $validated['target_date'] ?? null,
    ]);

    return redirect()->route('captain.proposal.index')->with('success', 'Proposal submitted successfully.');
}
public function update(Request $request, Proposal $proposal)
{
    // Ensure only the owner can update
    if ($proposal->captain_id !== Auth::id()) {
        abort(403, 'Unauthorized action.');
    }

    // ✅ Include missing fields in validation
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'source_of_fund' => 'nullable|string|max:255',
        'target_date' => 'nullable|date',
        'image' => 'nullable|image|max:2048',
        'file' => 'nullable|mimes:pdf,doc,docx|max:5120',
    ]);

    // ✅ Update image if provided
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('proposals/images', 'public');
        $proposal->image = $imagePath;
    }

    // ✅ Update file if provided
    if ($request->hasFile('file')) {
        $filePath = $request->file('file')->store('proposals/files', 'public');
        $proposal->file = $filePath;
    }

    // ✅ Update all fields
    $proposal->title = $validated['title'];
    $proposal->description = $validated['description'] ?? $proposal->description;
    $proposal->source_of_fund = $validated['source_of_fund'] ?? null;
    $proposal->target_date = $validated['target_date'] ?? null;

    // 🔄 Reset status if rejected
    if ($proposal->status === 'rejected') {
        $proposal->status = 'pending';
    }

    $proposal->save();

    return redirect()->route('captain.proposal.index')->with('success', 'Proposal updated successfully.');
}

public function approved()
{
    $proposals = Proposal::with('barangayInfo')
        ->where('captain_id', Auth::id())
        ->where('status', 'approved')
        ->latest()
        ->get();

    return view('captain.proposal.approved', compact('proposals'));
}
public function rejected()
{
    $proposals = Proposal::with('barangayInfo')
        ->where('captain_id', Auth::id())
        ->where('status', 'rejected')
        ->latest()
        ->get();

    return view('captain.proposal.rejected', compact('proposals'));
}
public function destroy(Proposal $proposal)
{
    // Siguraduhin na ang captain lang ang makaka-delete ng sarili niyang proposal
    if ($proposal->captain_id !== Auth::id()) {
        abort(403, 'Unauthorized action.');
    }

    $proposal->delete();

    return redirect()->route('captain.proposal.index')->with('success', 'Proposal deleted successfully.');
}
public function resubmit(Proposal $proposal)
{
    // Siguraduhin na captain lang ang makaka-resubmit ng sarili niyang proposal
    if ($proposal->captain_id !== Auth::id()) {
        abort(403, 'Unauthorized action.');
    }

    // Reset status to pending
    $proposal->status = 'pending';
    $proposal->rejection_reason = null; // optional: clear reason
    $proposal->save();

    return redirect()->route('captain.proposal.index')->with('success', 'Proposal resubmitted successfully.');
}
public function print(Proposal $proposal)
{
    // Load barangay at municipality, pati captain
    $proposal->load('barangayInfo.municipality', 'captain');

    return view('captain.proposal.print', compact('proposal'));
}


}