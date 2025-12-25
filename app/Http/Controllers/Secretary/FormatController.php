<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use App\Models\Format;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormatController extends Controller
{
    public function index()
    {
        $barangay_id = Auth::user()->barangay_id;
        $formats = Format::where('barangay_id', $barangay_id)->get();

        return view('secretary.formats.index', compact('formats'));
    }

    public function create()
    {
        return view('secretary.formats.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'logo' => 'nullable|image|mimes:jpg,jpeg,png,svg,gif|max:2048',
        'price' => 'required|numeric|min:0',
    ]);

    $logoPath = null;

    if ($request->hasFile('logo')) {
        $logoPath = $request->file('logo')->store('logos', 'public');
    }

    Format::create([
        'barangay_id' => Auth::user()->barangay_id,
        'title' => $request->title,
        'content' => $request->content,
        'logo_path' => $logoPath,
        'price' => $validated['price'],
    ]);

    return redirect()->route('secretary.formats.index')->with('success', 'Format added successfully.');
}


public function edit($id)
{
    $format = Format::findOrFail($id);
    return view('secretary.formats.edit', compact('format'));
}

public function update(Request $request, $id)
{
    $format = Format::findOrFail($id);

    $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required',
        'logo' => 'nullable|image|mimes:jpg,jpeg,png,svg,gif|max:2048',
    ]);

    $logoPath = $format->logo_path;

    if ($request->hasFile('logo')) {
        // Optional: delete old logo from storage
        if ($logoPath) {
            \Storage::disk('public')->delete($logoPath);
        }

        $logoPath = $request->file('logo')->store('logos', 'public');
    }

    $format->update([
        'title' => $request->title,
        'content' => $request->content,
        'logo_path' => $logoPath,
    ]);

    return redirect()->route('secretary.formats.index')->with('success', 'Format updated successfully!');
}

public function destroy($id)
{
    $format = Format::findOrFail($id);
    $format->delete();

    return redirect()->route('secretary.formats.index')->with('success', 'Format deleted successfully!');
}
public function apiFormats(Request $request)
{
    $barangayId = $request->query('barangay_id');

    if (!$barangayId) {
        return response()->json(['error' => 'barangay_id is required'], 400);
    }

    $formats = Format::where('barangay_id', $barangayId)->get();

    return response()->json($formats);
}
public function show($id)
{
    $format = Format::findOrFail($id);

    // Palitan ang @{{field}} ng {field} para mas madaling gamitan sa Flutter
    $content = preg_replace('/@\{\{(\w+)\}\}/', '{$1}', $format->content);

    return response()->json([
        'id' => $format->id,
        'title' => $format->title,
        'content' => $content,
        'logo_url' => $format->logo_path ? asset('storage/' . $format->logo_path) : null,
    ]);
}

}