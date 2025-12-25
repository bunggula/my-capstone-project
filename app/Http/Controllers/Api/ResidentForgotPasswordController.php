<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log; // ✅ Facade — this is correct

class ResidentForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:residents,email',
        ]);

        try {
            $status = Password::broker('residents')->sendResetLink(
                $request->only('email')
            );

            if ($status === Password::RESET_LINK_SENT) {
                Log::info('Reset link sent to: ' . $request->email); // ✅ Log works here
                return response()->json(['message' => 'Reset link sent successfully.']);
            }

            Log::warning('Failed to send reset link to: ' . $request->email); // ✅ optional
            return response()->json(['message' => 'Failed to send reset link.'], 500);
        } catch (\Exception $e) {
            Log::error('Reset error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
