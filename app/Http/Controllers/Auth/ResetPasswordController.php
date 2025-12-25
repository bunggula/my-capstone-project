<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function reset(Request $request)
    {
        $brokerName = $this->getBrokerName();
        $provider = config("auth.passwords.{$brokerName}.provider");
        $model = config("auth.providers.{$provider}.model");

        // Validation
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email|exists:' . (new $model)->getTable() . ',email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Get the broker instance
        $broker = Password::broker($brokerName);

        // Perform password reset
        $status = $broker->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->setRememberToken(Str::random(60));
                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    protected function getBrokerName()
    {
        if (request()->is('resident/*')) {
            return 'residents';
        }

        return 'users'; // default
    }
}
