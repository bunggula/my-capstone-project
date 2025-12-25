<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResidentApprovedNotification;
use App\Mail\ResidentRejectedNotification;

class SecretaryResidentController extends Controller
{
public function pending(Request $request)
{
    $barangayId = auth()->user()->barangay_id;

    $status = $request->get('status', 'pending'); // default pending
    $search = $request->get('search');

    // 📂 Base query
    $baseQuery = Resident::where('barangay_id', $barangayId);

   if ($search) {
    // Apply search filter including full name
    $baseQuery->where(function ($q) use ($search) {
        $q->where('first_name', 'like', "%{$search}%")
          ->orWhere('middle_name', 'like', "%{$search}%")
          ->orWhere('last_name', 'like', "%{$search}%")
          ->orWhere('email', 'like', "%{$search}%")
          ->orWhereRaw("CONCAT(first_name, ' ', middle_name, ' ', last_name) LIKE ?", ["%{$search}%"])
          ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
    });
}


   // 🔄 Auto-switch tab for search results without redirect
if ($search && !$request->has('status')) {
    $firstResult = (clone $baseQuery)->first();
    if ($firstResult) {
        $status = $firstResult->status;
    }
}

// 🔄 Apply status filter based on the (possibly updated) $status
if ($status !== 'all') {
    $baseQuery->where('status', $status);
}


    // 🔄 Apply status filter kung hindi "all"
    if ($status !== 'all') {
        $baseQuery->where('status', $status);
    }

// 📊 Count per status (filtered by search if applicable)
$countQuery = Resident::where('barangay_id', $barangayId);
if ($search) {
    $countQuery->where(function ($q) use ($search) {
        $q->where('first_name', 'like', "%{$search}%")
          ->orWhere('middle_name', 'like', "%{$search}%")
          ->orWhere('last_name', 'like', "%{$search}%")
          ->orWhere('email', 'like', "%{$search}%")
          ->orWhereRaw("CONCAT(first_name, ' ', middle_name, ' ', last_name) LIKE ?", ["%{$search}%"])
          ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
    });
}


    $counts = [
        'all'      => (clone $countQuery)->count(),
        'pending'  => (clone $countQuery)->where('status', 'pending')->count(),
        'approved' => (clone $countQuery)->where('status', 'approved')->count(),
        'rejected' => (clone $countQuery)->where('status', 'rejected')->count(),
    ];

    // 🔎 Pagkuha ng residents with barangay
    $allResidents = $baseQuery->with('barangay')
        ->orderByDesc('created_at')
        ->paginate(6)
        ->withQueryString();

    // 🔍 Detect re-registrations
    $allResidents->getCollection()->transform(function ($resident) {
        $previousRejected = \App\Models\Resident::where('first_name', $resident->first_name)
            ->where('middle_name', $resident->middle_name)
            ->where('last_name', $resident->last_name)
            ->where('suffix', $resident->suffix)
            ->where('birthdate', $resident->birthdate)
            ->where('status', 'rejected')
            ->exists();

        $resident->is_reregister = $previousRejected; // new property for view
        return $resident;
    });

    return view('secretary.residents.pending', compact('allResidents', 'counts', 'status', 'search'));
}


public function approve($id)
{
    $resident = Resident::findOrFail($id);

    if ($resident->barangay_id !== Auth::user()->barangay_id) {
        abort(403, 'Unauthorized action.');
    }

    $resident->status = 'approved';
    $resident->save();

    // Kunin yung secretary na nag-approve
    $approver = Auth::user();

    // Send notification email with approver info
    Mail::to($resident->email)->send(new ResidentApprovedNotification($resident, $approver));

    return redirect()->back()->with('success', 'Account Approved.');
}

public function reject(Request $request, $id)
{
    $resident = Resident::findOrFail($id);

    // Ensure admin is from the same barangay
    if ($resident->barangay_id !== Auth::user()->barangay_id) {
        abort(403, 'Unauthorized action.');
    }

    $request->validate([
        'reject_reason' => 'required|string|max:255',
        'other_reason' => 'nullable|string|max:255',
    ]);

    // Determine final reason
    $finalReason = $request->reject_reason === 'Other'
        ? $request->other_reason
        : $request->reject_reason;

    // Append to rejection history
    $history = $resident->rejection_reason_history
        ? json_decode($resident->rejection_reason_history, true)
        : [];

    $history[] = [
        'reason' => $finalReason,
        'rejected_by' => Auth::user()->name ?? Auth::user()->full_name,
        'date' => now(),
    ];

    // Update resident
    $resident->status = 'rejected';
    $resident->reject_reason = $finalReason;
    $resident->previously_rejected = true; // mark as previously rejected
       $resident->save();

    // Send email notification
    Mail::to($resident->email)->send(
        new ResidentRejectedNotification($resident, Auth::user(), $finalReason)
    );

    return redirect()->back()->with('error', 'Account rejected.');
}

}
