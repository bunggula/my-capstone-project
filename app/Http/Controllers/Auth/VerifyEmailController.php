<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->redirectByRole($request->user());
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return $this->redirectByRole($request->user());
    }

    protected function redirectByRole($user)
    {
        switch ($user->role) {
            case 'abc':
                return redirect()->route('abc.dashboard')->with('verified', true);
            case 'secretary':
                return redirect()->route('secretary.dashboard')->with('verified', true);
            case 'captain':
                return redirect()->route('captain.dashboard')->with('verified', true);
            default:
                return redirect('/')->with('verified', true); // fallback
        }
    }
}
