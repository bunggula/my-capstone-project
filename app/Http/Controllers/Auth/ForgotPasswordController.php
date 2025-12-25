<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $brokerName = $this->resolveBrokerName(); // ← this is correct method
        $provider = config("auth.passwords.{$brokerName}.provider");
        $model = config("auth.providers.{$provider}.model");

        $request->validate([
            'email' => 'required|email|exists:' . (new $model)->getTable() . ',email',
        ]);

        $status = Password::broker($brokerName)->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    protected function resolveBrokerName()
    {
        if (request()->is('resident/*')) {
            return 'residents';
        }

        return 'users'; // default
    }

    public function broker() // optional, if other parts use it
    {
        return Password::broker($this->resolveBrokerName());
    }
}
