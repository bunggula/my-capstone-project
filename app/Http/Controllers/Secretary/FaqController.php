<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;
use Illuminate\Support\Facades\Auth;

class FaqController extends Controller
{
    /**
     * Display list of FAQs
     */
    public function index()
{
    $barangay = Auth::user()->barangay;
    // Gumamit ng paginate, hal. 10 items per page
    $faqs = Faq::where('barangay_id', $barangay->id)->paginate(5);

    return view('secretary.faq.index', compact('faqs'));
}


    /**
     * Store a new FAQ
     */
    public function store(Request $request)
    {
        $barangay = Auth::user()->barangay;

        $data = $request->validate([
            'question' => 'required|string',
            'answer'   => 'required|string',
            'category' => 'nullable|string',
        ]);

        $data['barangay_id'] = $barangay->id;

        Faq::create($data);

        return redirect()->route('secretary.faq.index')
                         ->with('success', 'FAQ added successfully.');
    }

    /**
     * Update existing FAQ
     */
    public function update(Request $request, Faq $faq)
    {
        $data = $request->validate([
            'question' => 'required|string',
            'answer'   => 'required|string',
            'category' => 'nullable|string',
        ]);

        $faq->update($data);

        return redirect()->route('secretary.faq.index')
                         ->with('success', 'FAQ updated successfully.');
    }

    /**
     * Delete a FAQ
     */
    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()->route('secretary.faq.index')
                         ->with('success', 'FAQ deleted successfully.');
    }
}
