<?php


namespace App\Http\Controllers\Captain;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // ✅ <--- This is required!

class BarangayInfoController extends Controller
{
    public function edit()
    {
        $barangay = auth()->user()->barangay;

        $chairperson = User::where('barangay_id', $barangay->id)
            ->where('role', 'brgy_captain')
            ->first();

        $secretary = User::where('barangay_id', $barangay->id)
            ->where('role', 'secretary')
            ->first();

        return view('captain.barangay-info.edit', compact('barangay', 'chairperson', 'secretary'));
    }

    public function update(Request $request)
    {
        $barangay = auth()->user()->barangay;

        $data = $request->validate([
            'name' => 'required|string',
            'logo' => 'nullable|image|max:2048',
            'chairperson' => 'nullable|string',
            'secretary' => 'nullable|string',
            'contact_number' => 'nullable|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'office_hours' => 'nullable|string',
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('barangay_logos', 'public');
        }

        $barangay->update($data);

        return back()->with('success', 'Barangay info updated successfully.');
    }

   

    public function show()
    {
        $barangay = auth()->user()->barangay;
    
        $chairperson = User::where('barangay_id', $barangay->id)
            ->where('role', 'brgy_captain')
            ->first();
    
        $secretary = User::where('barangay_id', $barangay->id)
            ->where('role', 'secretary')
            ->first();
    
        $userEmail = auth()->user()->email;
    
        return view('captain.barangay-info.show', compact('barangay', 'chairperson', 'secretary', 'userEmail'));
    }
}    

