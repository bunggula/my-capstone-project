<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barangay;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;



class UserManagementController extends Controller
{
    public function showAddAccountForm()
    {
        $barangays = Barangay::all();
        $generatedPassword = Str::random(12);
        return view('abc.add_account', compact('barangays', 'generatedPassword'));
    }

    public function storeAccount(Request $request)
{
$validated = $request->validate([
'first_name'   => 'required|string|max:100',
'middle_name'  => 'nullable|string|max:100',
'last_name'    => 'required|string|max:100',
'suffix'       => 'nullable|string|max:20',
'barangay_id'  => 'required|exists:barangays,id',
'role'         => ['required', Rule::in(['brgy_captain'])],
'email'        => 'required|email|unique:users,email',
'gender'       => ['required', Rule::in(['male', 'female', 'other'])],
'birthday'     => 'required|date|before:today',
'start_year'   => 'required|digits:4|integer|min:2000|max:' . (date('Y') + 10),
'end_year'     => 'required|digits:4|integer|gte:start_year|max:' . (date('Y') + 10),
]);


// 🔒 Check kung may existing ACTIVE secretary/captain sa barangay 
$exists = User::where('barangay_id', $validated['barangay_id']) 
->where('role', $validated['role']) ->where('status', 'active') 
// ✅ only block if active
 ->first(); if ($exists)
  { $barangayName = $exists->barangay->name ?? 'Unknown'; return back() 
    ->with('error', ucfirst(str_replace('_', ' ', $validated['role'])) . " already exists in Barangay {$barangayName}.") 
    ->withInput(); }

// ✅ Generate temp password
$tempPassword = Str::random(10);

// ✅ Full name
$fullName = trim(
    $validated['first_name'] . ' ' .
    ($validated['middle_name'] ?? '') . ' ' .
    $validated['last_name'] . ' ' .
    ($validated['suffix'] ?? '')
);

// ✅ Create user
$user = User::create([
    'first_name'   => $validated['first_name'],
    'middle_name'  => $validated['middle_name'] ?? null,
    'last_name'    => $validated['last_name'],
    'suffix'       => $validated['suffix'] ?? null,
    'name'         => $fullName,
    'barangay_id'  => $validated['barangay_id'],
    'role'         => $validated['role'],
    'email'        => $validated['email'],
    'password'     => Hash::make($tempPassword),
    'gender'       => $validated['gender'],
    'birthday'     => $validated['birthday'],
    'status'       => 'active',
    'start_year'   => $validated['start_year'],
    'end_year'     => $validated['end_year'],
    'must_change_password' => true,
]);

// ✅ Send temp password via email
\Mail::raw("Hi {$fullName},\n\nYour temporary password is: {$tempPassword}\n\nPlease log in and change your password immediately.", function ($message) use ($user) {
    $message->to($user->email)
            ->subject('Your Temporary Account Password');
});

return redirect()->back()->with('success', 'Account created successfully! Temporary password sent to user\'s email.');


}
public function listAccounts(Request $request)
{
    $query = User::with('barangay')
        ->whereIn('role', ['secretary', 'brgy_captain', ]);

    // 🔍 Search filter
    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('first_name', 'like', '%' . $request->search . '%')
              ->orWhere('middle_name', 'like', '%' . $request->search . '%')
              ->orWhere('last_name', 'like', '%' . $request->search . '%')
              ->orWhere('email', 'like', '%' . $request->search . '%');
        });
    }

    // 🏷 Filter by barangay
    if ($request->filled('barangay')) {
        $query->where('barangay_id', $request->barangay);
    }

    // 👤 Filter by role
    if ($request->filled('role')) {
        $query->where('role', $request->role);
    }

    // ✅ Clone before status filter para magamit sa counts
    $baseQuery = clone $query;

    // 📌 Status filter (default active kung walang pinili)
    if ($request->filled('status')) {
        if ($request->status === 'inactive') {
            $query->where('status', 'inactive');
        } elseif ($request->status === 'all') {
            $query->whereIn('status', ['active', 'inactive']);
        } else {
            $query->where('status', 'active');
        }
    } else {
        $query->where('status', 'active'); // ✅ default active
    }

    // ✅ Dynamic status counts (respect filters pero ignore status)
    $activeCount = (clone $baseQuery)->where('status', 'active')->count();
    $inactiveCount = (clone $baseQuery)->where('status', 'inactive')->count();
    $statusCounts = [
        'active' => $activeCount,
        'inactive' => $inactiveCount,
    ];

    // ✅ Paginate users, ordered by barangay name alphabetically
 $users = $query->join('barangays', 'users.barangay_id', '=', 'barangays.id')
               ->orderBy('barangays.name')
               ->orderBy('users.last_name')
               ->select('users.*')
               ->paginate(10)
               ->appends($request->all()); // 🔑 preserve filters on pagination


    $barangays = Barangay::all();

    return view('abc.list_accounts', compact('users', 'barangays', 'statusCounts'));
}



    
    public function editAccount($id)
    {
        $user = User::findOrFail($id);
        $barangays = Barangay::all();
        return view('abc.edit_account', compact('user', 'barangays'));
    }

   public function updateAccount(Request $request, $id)
{
    $user = User::findOrFail($id);

    // 🔍 Validate inputs
    $validated = $request->validate([
        'first_name'   => 'required|string|max:100',
        'middle_name'  => 'nullable|string|max:100',
        'last_name'    => 'required|string|max:100',
        'suffix'       => 'nullable|string|max:20',
        'barangay_id'  => 'required|exists:barangays,id',
        'role'         => ['required', Rule::in(['brgy_captain'])],
        'email'        => 'required|email|unique:users,email,' . $user->id,
        'gender'       => ['required', Rule::in(['male', 'female', 'other'])],
        'birthday'     => 'required|date|before:today',
        'start_year'   => 'required|digits:4|integer|min:2000|max:' . (date('Y') + 10),
        'end_year'     => 'required|digits:4|integer|gte:start_year|max:' . (date('Y') + 10),
    ]);

    // 🔒 Check kung may existing *ACTIVE* secretary/captain sa barangay (maliban sa kasalukuyang user)
    $exists = User::where('barangay_id', $validated['barangay_id'])
        ->where('role', $validated['role'])
        ->where('status', 'active') // ✅ important: same check as storeAccount()
        ->where('id', '!=', $user->id)
        ->first();

    if ($exists) {
        $barangayName = $exists->barangay->name ?? 'Unknown';
        return back()
            ->with('error', ucfirst(str_replace('_', ' ', $validated['role'])) . 
                  " already exists in Barangay {$barangayName}. Please deactivate the existing one first.")
            ->withInput();
    }

    // ✅ Update full name (optional improvement)
    $validated['name'] = trim(
        $validated['first_name'] . ' ' .
        ($validated['middle_name'] ?? '') . ' ' .
        $validated['last_name'] . ' ' .
        ($validated['suffix'] ?? '')
    );

    // ✅ Update user
    $user->update($validated);

   return redirect()->route('abc.accounts.list')
    ->with('success', 'Account updated successfully.');

}

    
    public function deleteAccount(User $user)
    {
        $user->delete();
        return redirect()->route('abc.accounts.list')->with('success', 'Account deleted successfully.');
    }
   public function toggleStatus($id)
{
    $user = User::findOrFail($id);

    // Only allow toggling for Barangay Captain
    if ($user->role !== 'brgy_captain') {
        return back()->with('error', 'Only Barangay Captains can be activated/deactivated here.');
    }

    $user->status = $user->status === 'active' ? 'inactive' : 'active';
    $user->save();

    return back()->with('success', 'Account status updated successfully.');
}

// ABCController.php
public function show($id)
{
    $user = User::with('barangay')->findOrFail($id);
    return view('abc.view_account', compact('user'));
}

}    

