<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class ResidentResetPasswordController extends Controller
{
    // Show the password reset form
    public function showResetForm(Request $request, $token = null)
    {
        return view('resident.auth.passwords.reset', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    // Handle password reset submission
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:residents,email',
            'password' => 'required|string|min:8|confirmed',
        ]);
    
        $status = Password::broker('residents')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($resident, $password) {
                $resident->password = Hash::make($password);
                $resident->setRememberToken(Str::random(60));
                $resident->save();
    
                event(new PasswordReset($resident));
            }
        );
    
        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'message' => 'Password reset successfully.',
            ]);
        } else {
            return response()->json([
                'message' => 'Failed to reset password.',
                'error' => __($status),
            ], 422);
        }
    }
    
}
