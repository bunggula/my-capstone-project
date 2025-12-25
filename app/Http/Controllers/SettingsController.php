<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;  // <-- Add this

class SettingsController extends Controller
{
    // Show edit profile form
    public function editProfile()
    {
        $user = Auth::user();
        return view('settings.profile', compact('user'));
    }

    // Update profile info
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
    
        $request->validate([
            'first_name'   => 'required|string|max:255',
            'middle_name'  => 'nullable|string|max:255',
            'last_name'    => 'required|string|max:255',
            'suffix'       => 'nullable|string|max:10',
            'email'        => 'required|email|unique:users,email,' . $user->id,
            'gender'       => 'required|in:male,female',
            'birthday'     => 'required|date|before:today',
        ]);
    
        $user->first_name   = $request->first_name;
        $user->middle_name  = $request->middle_name;
        $user->last_name    = $request->last_name;
        $user->suffix       = $request->suffix;
        $user->gender       = $request->gender;
        $user->birthday     = $request->birthday;
    
        if ($request->email !== $user->email) {
            $user->email = $request->email;
            $user->email_verified_at = null;
            $user->save();
            $user->sendEmailVerificationNotification();
    
            return back()->with('success', 'Profile updated! Please verify your new email.');
        }
    
        $user->save();
    
        return back()->with('success', 'Profile updated successfully!');
    }
    

    // Show change password form
    public function editPassword()
    {
        return view('settings.password');
    }

    // Update password logic
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
    
        // Kung first login at required siyang magpalit ng password
        if ($user->must_change_password) {
            $request->validate([
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);
        } else {
            // Normal change password (with current password check)
            $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);
        }
    
        $user->password = Hash::make($request->password);
        $user->must_change_password = false; // ✅ alisin flag kapag nagpalit na
        $user->save();
    
        return redirect()->route('settings.password')->with('success', 'Password updated successfully!');
    }
    
}
