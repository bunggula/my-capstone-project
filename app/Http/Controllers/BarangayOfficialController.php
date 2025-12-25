<?php

namespace App\Http\Controllers;

use App\Models\BarangayOfficial;
use Illuminate\Http\Request;

class BarangayOfficialController extends Controller
{
    public function show($id)
    {
        // Fetch official with barangay relation
        $official = BarangayOfficial::with('barangay')->findOrFail($id);

        // Decode categories if stored as JSON
        if (is_string($official->categories)) {
            $official->categories = json_decode($official->categories, true);
        }

        // Return view modal
        return view('abc.modals.view_official', compact('official'));
    }
}
