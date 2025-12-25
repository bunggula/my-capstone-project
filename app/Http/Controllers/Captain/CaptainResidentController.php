<?php
namespace App\Http\Controllers\Captain;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class CaptainResidentController extends Controller
{
    public function index(Request $request)
    {
        $barangayId = auth()->user()->barangay_id;
    
        $status   = $request->input('status', 'approved'); // approved / archived
        $search   = $request->input('search');
        $category = $request->input('category');
        $voter    = $request->input('voter'); // Yes / No
        $gender   = $request->input('gender'); // Male / Female
    
        // Base query for counts (ignore gender for cards)
        $countBase = Resident::where('barangay_id', $barangayId)
                             ->when($search, fn($q) => $q->where(function($q2) use ($search) {
                                 $q2->where('first_name', 'like', "%{$search}%")
                                    ->orWhere('middle_name', 'like', "%{$search}%")
                                    ->orWhere('last_name', 'like', "%{$search}%");
                             }))
                             ->when($category && $category !== 'Archived', function($q) use ($category) {
                                 if ($category === 'Minor') {
                                     $q->whereNotNull('birthdate')
                                       ->whereRaw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) < 18');
                                 } elseif ($category === 'Adult') {
                                     $q->whereNotNull('birthdate')
                                       ->whereRaw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) >= 18');
                                 } else {
                                     $q->where('category', 'like', "%{$category}%");
                                 }
                             })
                             ->when($voter, fn($q) => $q->where('voter', $voter));
    
        // Counts
        $counts = [
            'all'      => (clone $countBase)->where('status', 'approved')->count(),
            'archived' => (clone $countBase)->where('status', 'archived')->count(),
            'male'     => (clone $countBase)->where('status', 'approved')->where('gender', 'Male')->count(),
            'female'   => (clone $countBase)->where('status', 'approved')->where('gender', 'Female')->count(),
        ];
    
        // Base query for table
        $query = Resident::where('barangay_id', $barangayId)
                         ->when($status === 'archived', fn($q) => $q->where('status', 'archived'))
                         ->when($status !== 'archived', fn($q) => $q->where('status', 'approved'))
                         ->when($search, fn($q) => $q->where(function($q2) use ($search) {
                             $q2->where('first_name', 'like', "%{$search}%")
                                ->orWhere('middle_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                         }))
                         ->when($category && $category !== 'Archived', function($q) use ($category) {
                             if ($category === 'Minor') {
                                 $q->whereNotNull('birthdate')
                                   ->whereRaw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) < 18');
                             } elseif ($category === 'Adult') {
                                 $q->whereNotNull('birthdate')
                                   ->whereRaw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) >= 18');
                             } else {
                                 $q->where('category', 'like', "%{$category}%");
                             }
                         })
                         ->when($voter, fn($q) => $q->where('voter', $voter))
                         ->when($gender, fn($q) => $q->where('gender', $gender));
    
        $residents = $query->orderBy('first_name')->paginate(5)->withQueryString();
    
        return view('captain.residents.index', compact(
            'residents', 'counts', 'status', 'search', 'category', 'voter', 'gender'
        ));
    }
    
    
    
    public function exportPDF()
    {
        $residents = Resident::with('barangay')
            ->where('barangay_id', auth()->user()->barangay_id)
            ->get();
    
        $pdf = Pdf::loadView('exports.captain-residents-pdf', compact('residents'));
        return $pdf->download('barangay_residents.pdf');
    }
    public function print(Request $request)
    {
        $barangayId = auth()->user()->barangay_id;
        $search = $request->input('search');
        $category = $request->input('category');
        $voter = $request->input('voter') ? ucfirst($request->input('voter')) : null;
    
        $query = Resident::with('barangay')
            ->where('barangay_id', $barangayId)
            ->where('status', 'Approved')
            ->when($search, function($q) use ($search) {
                $q->where(function($q2) use ($search) {
                    $q2->where('first_name', 'like', "%{$search}%")
                       ->orWhere('middle_name', 'like', "%{$search}%")
                       ->orWhere('last_name', 'like', "%{$search}%");
                });
            })
            ->when($category, function($q) use ($category) {
                if ($category === 'Minor') {
                    $q->whereNotNull('birthdate')
                      ->whereRaw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) < 18');
                } elseif ($category === 'Adult') {
                    $q->whereNotNull('birthdate')
                      ->whereRaw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) >= 18');
                } else {
                    $q->where('category', 'like', "%{$category}%");
                }
            })
            ->when($voter, function($q) use ($voter) {
                $q->where('voter', $voter);
            });
    
        $residents = $query->orderBy('first_name')->get();
        $barangayName = auth()->user()->barangay->name ?? 'N/A';
    
        return view('captain.residents.print', compact('residents', 'barangayName', 'search', 'category', 'voter'));
    }
    

}    


