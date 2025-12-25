<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Concern;
use Illuminate\Support\Facades\Storage;

class ConcernController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'barangay_id' => 'required|exists:barangays,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'zone' => 'required|string',
            'street' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10000',
        ]);
    
        $validated['resident_id'] = auth()->id();
        $validated['status'] = 'pending'; // ← ADD THIS LINE
    
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('concern_images', 'public');
            $validated['image_path'] = $path;
        }
    
        $concern = Concern::create($validated);
    
        return response()->json([
            'success' => true,
            'message' => 'Concern submitted successfully',
            'data' => $concern,
        ]);
    }
    
    // GET /api/user/concerns
    public function residentConcerns()
    {
        $residentId = auth()->id(); // ← ito lang ang concerns na kukunin
    
        $concerns = \App\Models\Concern::where('resident_id', $residentId)
            ->orderBy('created_at', 'desc')
            ->get();
    
        return response()->json([
            'success' => true,
            'data' => $concerns,
        ]);
    }
    
    
}
