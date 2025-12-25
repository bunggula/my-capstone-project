<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\JsonResponse;

class FaqController extends Controller
{
   public function index()
{
    $user = auth()->user(); // galing sa residents table
    $barangayId = $user->barangay_id;

    $faqs = Faq::where('barangay_id', $barangayId)
                ->orderBy('id', 'desc')
                ->get();

    return response()->json([
        'success' => true,
        'data' => $faqs,
    ]);
}

}