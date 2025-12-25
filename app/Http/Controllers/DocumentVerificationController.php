<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequest;
use Illuminate\Http\Request;

class DocumentVerificationController extends Controller
{
    public function verify($reference_code)
    {
        $request = DocumentRequest::with('resident')->where('reference_code', $reference_code)->first();

        if (!$request) {
            return view('verification.invalid');
        }

        return view('verification.valid', compact('request'));
    }
}
