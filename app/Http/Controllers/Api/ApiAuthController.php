<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ApiAuthController extends Controller
{
    // 🟩 REGISTER
    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'last_name' => 'required|string',
            'suffix' => 'nullable|string',
            'gender' => 'required|string',
            'birthdate' => 'required|date',
            'age' => 'required|integer',
            'civil_status' => 'required|string',
            'category' => 'nullable|string',
            'email' => 'required|email|unique:residents,email',
            'phone' => 'required|string',
            'barangay_id' => 'required|integer',
            'address' => 'required|string',
            'password' => 'required|string|confirmed',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['status'] = 'pending'; // wait for approval

        $resident = Resident::create($validated);

        return response()->json([
            'message' => 'Registration submitted. Please wait for approval.',
            'data' => $resident
        ], 201);
    }

  // 🟩 LOGIN
public function login(Request $request)
{
    // 1️⃣ Validate request
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    // 2️⃣ Find resident by email
    $resident = Resident::where('email', $request->email)->first();

    // 3️⃣ Check credentials
    if (!$resident || !Hash::check($request->password, $resident->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    // 4️⃣ Check if approved
    if ($resident->status !== 'approved') {
        return response()->json(['message' => 'Account not approved yet'], 403);
    }

    // 5️⃣ Generate Sanctum token
    $token = $resident->createToken('auth_token')->plainTextToken;

    // 6️⃣ Return JSON response with top-level must_change_password
    return response()->json([
        'token' => $token,
        'user' => $resident,
        'must_change_password' => $resident->must_change_password, // 🔹 Important for Flutter
    ]);
}


    // 🟩 LOGOUT
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully.'
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


}
