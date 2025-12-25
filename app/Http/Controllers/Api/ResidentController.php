<?php

    namespace App\Http\Controllers\Api;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Password;
    use Illuminate\Validation\Rule; 
    class ResidentController extends Controller
    {
        public function store(Request $request)
        {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'last_name' => 'required|string|max:255',
                'suffix' => 'nullable|string|max:10',
                'gender' => 'required|in:Male,Female',
                'birthdate' => 'required|date',
                'age' => 'required|integer|min:0',
                'civil_status' => 'required|string',
                'category' => 'nullable|string',
                'email' => [
    'required',
    'email',
    Rule::unique('residents')->where(function ($query) {
        return $query->whereIn('status', ['pending', 'approved']);
    }),
],
                'phone' => 'required|string|max:20',
                'barangay_id' => 'required|exists:barangays,id',
                'zone' => 'required|string|max:50',
             
                'password' => 'required|string|min:6',
                'proof_of_residency' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ]);
        
            if ($request->hasFile('proof_of_residency')) {
                $validated['proof_of_residency'] = $request->file('proof_of_residency')->store('proofs', 'public');
            }
        
            $validated['password'] = bcrypt($validated['password']);
        
            $resident = \App\Models\Resident::create($validated);
        
            return response()->json([
                'message' => 'Resident registered successfully. Please wait for approval.',
                'resident' => $resident,
            ], 201);
        }
        
        public function login(Request $request)
        {
            // 1️⃣ Validate request
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);
        
            // 2️⃣ Find resident by email
            $resident = \App\Models\Resident::where('email', $request->email)->first();
        
            // 3️⃣ Check credentials
            if (!$resident || !\Hash::check($request->password, $resident->password)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
        
            // 4️⃣ Check account status
            $status = strtolower(trim($resident->status)); // normalize status
        
            if ($status === 'archived') {
                return response()->json(['message' => 'Account archived'], 423); // archived account
            }
        
            if ($status !== 'approved') {
                return response()->json(['message' => 'Account not approved yet'], 403); // not approved
            }
        
            // 5️⃣ Generate Sanctum token
            $token = $resident->createToken('auth_token')->plainTextToken;
        
            // 6️⃣ Return JSON response
            return response()->json([
                'token' => $token,
                'user' => $resident,
                'must_change_password' => $resident->must_change_password, // optional, for Flutter flow
            ]);
        }
        
    public function sendPasswordReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:residents,email',
        ]);

        // Gumamit ng custom password broker kung may `residents` password broker ka.
        $status = Password::broker('residents')->sendResetLink(
            ['email' => $request->email]
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Reset link sent successfully.']);
        }

        return response()->json(['message' => 'Failed to send reset link.'], 500);
    }
    public function profile(Request $request)
    {
        return response()->json($request->user());
    }
    public function update(Request $request, $id)
    {
        $resident = \App\Models\Resident::findOrFail($id);
    
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'gender' => 'required|in:Male,Female',
            'birthdate' => 'required|date',
            'age' => 'required|integer|min:0',
            'civil_status' => 'required|string',
            'category' => 'nullable|string',
            'phone' => 'required|string|max:20',
           
            'zone' => 'required|string|max:50',
            'email' => 'required|email|unique:residents,email,' . $id,
            'password' => 'nullable|string|min:6', // optional password change
            'proof_of_residency' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'voter' => 'nullable|in:Yes,No',
        ]);
    
        if ($request->hasFile('proof_of_residency')) {
            $validated['proof_of_residency'] = $request->file('proof_of_residency')->store('proofs', 'public');
        }
    
        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']); // don’t update password if not given
        }
    
        $resident->update($validated);
    
        return response()->json([
            'message' => 'Profile updated successfully.',
            'resident' => $resident
        ]);
    }
    
    public function registerWithProof(Request $request)
{
    // 1️⃣ Validate request
    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'middle_name' => 'nullable|string|max:255',
        'last_name' => 'required|string|max:255',
        'suffix' => 'nullable|string|max:10',
        'gender' => 'required|in:Male,Female',
        'birthdate' => 'required|date',
        'age' => 'required|integer|min:0',
        'civil_status' => 'required|string|max:50',
        'category' => 'nullable|string|max:100',
        'zone' => 'required|string|max:50',
        'email' => ['required', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
        'phone' => 'required|string|max:20',
        'barangay_id' => 'required|exists:barangays,id',
        'password' => 'required|string|min:6',
        'voter' => 'required|in:Yes,No',
        'proof_type' => 'required|string|max:50',
        'proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
    ]);

    // 2️⃣ Check if a previously rejected record exists
    $existingRejected = \App\Models\Resident::where('email', $validated['email'])
        ->where('status', 'rejected')
        ->first();

    // 📂 Store uploaded proof
    if ($request->hasFile('proof')) {
        $filePath = $request->file('proof')->store('proofs', 'public');
        $validated['proof_of_residency'] = $filePath;
    }

    if ($existingRejected) {
        // 3️⃣ Update the rejected record with new info
        $existingRejected->update([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'suffix' => $validated['suffix'] ?? null,
            'gender' => $validated['gender'],
            'birthdate' => $validated['birthdate'],
            'age' => $validated['age'],
            'civil_status' => $validated['civil_status'],
            'category' => $validated['category'] ?? null,
            'zone' => $validated['zone'],
            'phone' => $validated['phone'],
            'barangay_id' => $validated['barangay_id'],
            'status' => 'pending',
            'password' => bcrypt($validated['password']),
            'proof_of_residency' => $validated['proof_of_residency'] ?? null,
            'voter' => $validated['voter'],
            'proof_type' => $validated['proof_type'],
            'must_change_password' => false,
            'previously_rejected' => true,
            'rejection_reason_history' => json_encode([
                'old_reason' => $existingRejected->reject_reason,
                'date' => now(),
            ])
        ]);

        $resident = $existingRejected;
    } else {
        // 4️⃣ Regular duplicate checks (ignoring rejected records)
        $emailDuplicate = \App\Models\Resident::with('barangay')
            ->where('email', $validated['email'])
            ->where('status', '!=', 'rejected')
            ->first();

        if ($emailDuplicate) {
            return response()->json([
                'message' => 'This email is already used in Barangay ' . $emailDuplicate->barangay->name . '.',
                'errors' => [
                    'email' => ['This email is already used in Barangay ' . $emailDuplicate->barangay->name . '.']
                ]
            ], 422);
        }

        $barangayDuplicate = \App\Models\Resident::where('barangay_id', $validated['barangay_id'])
            ->where('first_name', $validated['first_name'])
            ->where('middle_name', $validated['middle_name'])
            ->where('last_name', $validated['last_name'])
            ->where('suffix', $validated['suffix'])
            ->where('birthdate', $validated['birthdate'])
            ->where('status', '!=', 'rejected')
            ->first();

        if ($barangayDuplicate) {
            return response()->json([
                'message' => 'This resident already exists in your barangay.'
            ], 422);
        }

        $systemDuplicate = \App\Models\Resident::with('barangay')
            ->where('first_name', $validated['first_name'])
            ->where('middle_name', $validated['middle_name'])
            ->where('last_name', $validated['last_name'])
            ->where('suffix', $validated['suffix'])
            ->where('birthdate', $validated['birthdate'])
            ->where('barangay_id', '!=', $validated['barangay_id'])
            ->where('status', '!=', 'rejected')
            ->first();

        if ($systemDuplicate) {
            return response()->json([
                'message' => 'This resident already exists in another barangay (' 
                    . $systemDuplicate->barangay->name . ').'
            ], 422);
        }

        // 5️⃣ Create new resident record
        $resident = \App\Models\Resident::create([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'suffix' => $validated['suffix'] ?? null,
            'gender' => $validated['gender'],
            'birthdate' => $validated['birthdate'],
            'age' => $validated['age'],
            'civil_status' => $validated['civil_status'],
            'category' => $validated['category'] ?? null,
            'zone' => $validated['zone'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'barangay_id' => $validated['barangay_id'],
            'status' => 'pending',
            'password' => bcrypt($validated['password']),
            'proof_of_residency' => $validated['proof_of_residency'] ?? null,
            'voter' => $validated['voter'],
            'proof_type' => $validated['proof_type'],
            'must_change_password' => false,
        ]);
    }

    // 6️⃣ Return response
    return response()->json([
        'message' => 'Registration successful. Please wait for approval. We will notify you through your email once approved.',
        'resident' => $resident
    ], 201);
}

    
    public function uploadProfilePicture(Request $request)
    {
        $user = $request->user(); // authenticated resident
    
        if (!$request->hasFile('image')) {
            return response()->json(['message' => 'No image uploaded'], 400);
        }
    
        $file = $request->file('image');
        $filename = time().'_'.$file->getClientOriginalName();
    
        // Save to storage
        $path = $file->storeAs('profile_pictures', $filename, 'public');

    
        // Save the image path to database
        $user->profile_picture = 'storage/profile_pictures/' . $filename;
        $user->save();
    
        return response()->json([
            'message' => 'Profile picture updated successfully.',
            'image_url' => asset('storage/profile_pictures/' . $filename),
        ]);
    }
    public function changePassword(Request $request)
{
    $request->validate([
        'new_password' => 'required|string|min:8|confirmed'
    ]);

    $resident = $request->user(); // Sanctum-authenticated Resident

    $resident->password = bcrypt($request->new_password);
    $resident->must_change_password = 0;
    $resident->save();

    return response()->json(['message' => 'Password updated successfully']);
}
public function changePasswordWithOld(Request $request)
{
    $request->validate([
        'old_password' => 'required|string',
        'new_password' => 'required|string|min:8|confirmed',
    ]);

    $resident = $request->user(); // Sanctum-authenticated Resident

    // 🔑 Check old password
    if (!\Hash::check($request->old_password, $resident->password)) {
        return response()->json([
            'message' => 'Old password is incorrect'
        ], 422);
    }

    // 🔄 Update new password
    $resident->password = bcrypt($request->new_password);
    $resident->must_change_password = 0; // reset force-change flag
    $resident->save();

    return response()->json(['message' => 'Password updated successfully']);
}

    }
