<?php

namespace App\Http\Controllers\Captain;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SecretaryAccountController extends Controller

{
    public function index()
{
    $barangayId = auth()->user()->barangay_id;

    // Get all secretaries of this barangay
    $secretaries = User::where('barangay_id', $barangayId)
        ->where('role', 'secretary')
        ->get();

    return view('captain.secretary.index', compact('secretaries'));
}

    public function create()
    {
        $barangayId = auth()->user()->barangay_id;

        // Check if barangay already has an active secretary
        $existing = User::where('barangay_id', $barangayId)
            ->where('role', 'secretary')
            ->where('status', 'active')
            ->first();

        return view('captain.secretary.create', compact('existing'));
    }

  public function store(Request $request)
{
    $barangayId = auth()->user()->barangay_id;

    // Check if barangay already has an active secretary
    $existing = User::where('barangay_id', $barangayId)
        ->where('role', 'secretary')
        ->where('status', 'active')
        ->first();

    if ($existing) {
        return back()->with('error', 'Your barangay already has an active Secretary.');
    }

    // Validate input
    $validated = $request->validate([
        'first_name' => 'required|string|max:100',
        'middle_name' => 'nullable|string|max:100',
        'last_name' => 'required|string|max:100',
        'email' => 'required|email|unique:users,email',
        'gender' => 'required|in:male,female,other',
        'birthday' => 'required|date|before:today',
    ]);

    // Generate temporary password
    $tempPassword = \Illuminate\Support\Str::random(10);

    $fullName = $validated['first_name'] . ' ' . ($validated['middle_name'] ?? '') . ' ' . $validated['last_name'];

    // Create the secretary
    $user = User::create([
        'first_name' => $validated['first_name'],
        'middle_name' => $validated['middle_name'],
        'last_name' => $validated['last_name'],
        'name' => $fullName,
        'email' => $validated['email'],
        'password' => \Illuminate\Support\Facades\Hash::make($tempPassword),
        'gender' => $validated['gender'],
        'birthday' => $validated['birthday'],
        'barangay_id' => $barangayId,
        'role' => 'secretary',
        'status' => 'active',
        'must_change_password' => true,
    ]);

    // Send temporary password via email
    \Mail::raw(
        "Hi {$fullName},\n\nYour temporary password is: {$tempPassword}\n\nPlease change it upon login.",
        function ($msg) use ($user) {
            $msg->to($user->email)->subject('Your Secretary Account Password');
        }
    );

    return back()->with('success', 'Secretary created successfully and password sent via email.');
}

    public function toggleStatus(User $user)
{
    // Ensure only secretary can be toggled
    if ($user->role !== 'secretary') {
        return back()->with('error', 'Only secretary accounts can be toggled.');
    }

    // Ensure the secretary belongs to captain's barangay
    if ($user->barangay_id !== auth()->user()->barangay_id) {
        return back()->with('error', 'Unauthorized action.');
    }

    // Toggle status
    $user->status = $user->status === 'active' ? 'inactive' : 'active';
    $user->save();

    $message = $user->status === 'active' ? 'Secretary activated.' : 'Secretary deactivated.';
    return back()->with('success', $message);
}
// Show edit form

// Update secretary info
public function update(Request $request, User $user)
{
    // Validation
    $validated = $request->validate([
        'first_name' => 'required|string|max:100',
        'middle_name' => 'nullable|string|max:100',
        'last_name' => 'required|string|max:100',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'gender' => 'required|in:male,female,other',
        'birthday' => 'required|date|before:today',
    ]);

    $user->update($validated);

    // Update full name
    $user->name = $validated['first_name'] . ' ' . ($validated['middle_name'] ?? '') . ' ' . $validated['last_name'];
    $user->save();

    return back()->with('success', 'Secretary updated successfully.');
}

}
