<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Municipality;
class BarangayController extends Controller
{
    // Display all barangays view
    public function index()
    {
        // Eager load municipality relation
        $barangays = Barangay::with('municipality')->get();
        return view('abc.barangays.index', compact('barangays'));
    }
    
    
    // Get all barangays JSON (for API)
    public function getBarangays()
    {
        return response()->json(Barangay::select('id', 'name')->get());
    }

    // Get barangay officials
    public function getOfficials($id)
    {
        $captain = User::where('barangay_id', $id)
            ->where('role', 'brgy_captain')
            ->first();

        $secretary = User::where('barangay_id', $id)
            ->where('role', 'secretary')
            ->first();

        return response()->json([
            'captain' => $captain ? trim("{$captain->first_name} {$captain->middle_name} {$captain->last_name} {$captain->suffix}") : null,
            'secretary' => $secretary ? trim("{$secretary->first_name} {$secretary->middle_name} {$secretary->last_name} {$secretary->suffix}") : null,
        ]);
    }

    // Add new barangay
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Barangay::create($request->only('name'));

        return response()->json(['success' => true, 'message' => 'Barangay added!']);
    }

    // Update barangay
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
    
        $barangay = Barangay::findOrFail($id);
        $barangay->update($request->only('name'));
    
        return response()->json(['success' => true, 'message' => 'Barangay updated!']);
    }
    
    public function destroy($id)
    {
        $barangay = Barangay::findOrFail($id);
        $barangay->delete();
    
        return response()->json(['success' => true, 'message' => 'Barangay deleted!']);
    }
    // Update the municipality name
    public function updateMunicipality(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // max 2MB
        ]);
    
        $municipality = Municipality::findOrFail($id);
    
        $data = ['name' => $request->name];
    
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $path = $file->store('municipalities', 'public'); // stores in storage/app/public/municipalities
            $data['logo'] = $path;
        }
    
        $municipality->update($data);
    
        return response()->json([
            'success' => true,
            'message' => 'Municipality updated!',
            'logo_url' => isset($data['logo']) ? asset('storage/' . $data['logo']) : null
        ]);
    }
    

}
