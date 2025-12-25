<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Models\Barangay;
use App\Models\BarangayOfficial;

class ABCProposalController extends Controller
{
  public function index(Request $request)
{
    $status = $request->query('status', 'pending'); // default to pending
    $search = $request->query('search');
    $barangayId = $request->query('barangay');
    $month = $request->query('month');
    $year = $request->query('year');

    $query = Proposal::with(['barangayInfo', 'captain']);

    // 🔍 Search filter
    if ($search) {
        $query->where('title', 'like', "%{$search}%");
    }

    // 📌 Status filter (skip if "all")
    if ($status && $status !== 'all') {
        $query->where('status', $status);
    }

    // 🏘️ Barangay filter
    if ($barangayId) {
        $query->where('barangay', $barangayId);
    }

    // 🗓 Month filter
    if ($month) {
        $query->whereMonth('created_at', $month);
    }

    // 📅 Year filter
    if ($year) {
        $query->whereYear('created_at', $year);
    }

    // 📋 Fetch paginated results (with filters preserved in query string)
    $proposals = $query->latest()->paginate(10)->withQueryString();

    // 🧑‍💼 Add SK Chairman info
    $proposals->each(function ($proposal) {
        $sk = \App\Models\BarangayOfficial::where('barangay_id', $proposal->barangay)
            ->where('position', 'SK Chairman')
            ->first();

        $proposal->sk_chairman_name = $sk 
            ? trim("{$sk->first_name} {$sk->middle_name} {$sk->last_name} {$sk->suffix}") 
            : 'N/A';
    });

    // 📊 Count stats with filters
    $countQuery = Proposal::query();

    if ($search) {
        $countQuery->where('title', 'like', "%{$search}%");
    }

    if ($barangayId) {
        $countQuery->where('barangay', $barangayId);
    }

    if ($month) {
        $countQuery->whereMonth('created_at', $month);
    }

    if ($year) {
        $countQuery->whereYear('created_at', $year);
    }

    $counts = [
        'pending'  => (clone $countQuery)->where('status', 'pending')->count(),
        'approved' => (clone $countQuery)->where('status', 'approved')->count(),
        'rejected' => (clone $countQuery)->where('status', 'rejected')->count(),
        'all'      => (clone $countQuery)->count(),
    ];

    $currentStatus = $status;
    $statusLabel = $status !== 'all' ? ucfirst($status) . ' Proposals' : 'All Proposals';
    $barangays = Barangay::orderBy('name')->get();

    return view('abc.proposals.index', compact(
        'proposals', 
        'counts', 
        'statusLabel', 
        'currentStatus', 
        'barangays', 
        'barangayId', 
        'search',
        'month',
        'year'
    ));
}



    
    // ✅ Show single proposal (optional if using modal)
    public function show($id)
    {
        $proposal = Proposal::with(['barangayInfo', 'captain'])->findOrFail($id);
        return view('abc.proposals.show', compact('proposal'));
    }

    // ✅ Approve proposal
    public function approve($id)
    {
        $proposal = Proposal::findOrFail($id);
        $proposal->status = 'approved';
        $proposal->save();

        return redirect()->back()->with('success', '✅ Proposal approved successfully.');
    }

    // ✅ Reject proposal
    public function reject(Request $request, $id)
    {
        $proposal = Proposal::findOrFail($id);

        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $proposal->status = 'rejected';
        $proposal->rejection_reason = $request->input('rejection_reason');
        $proposal->save();

        return redirect()->back()->with('error', 'Proposal rejected with reason.');
    }

    // ✅ View only approved proposals
    public function approved()
    {
        $proposals = Proposal::with(['barangayInfo', 'captain'])
            ->where('status', 'approved')->latest()->get();

        $statusLabel = 'Approved Proposals';
        return view('abc.proposals.index', compact('proposals', 'statusLabel'));
    }

    // ✅ View only rejected proposals
    public function rejected()
    {
        $proposals = Proposal::with(['barangayInfo', 'captain'])
            ->where('status', 'rejected')->latest()->get();

        $statusLabel = 'Rejected Proposals';
        return view('abc.proposals.index', compact('proposals', 'statusLabel'));
    }
    // ✅ Print Proposal Letter
  // ABCProposalController.php
public function print(Proposal $proposal)
{
    return view('abc.proposals.print', compact('proposal'));
}


}
