<?php

namespace App\Http\Controllers;

use App\Models\VawcReport;
use Illuminate\Http\Request;
use App\Models\BarangayOfficial; 
use App\Models\User;

class VawcReportController extends Controller
{
  public function index(Request $request)
{
    $user = auth()->user();
    $month = $request->input('month');

    $query = VawcReport::with(['cases', 'programs'])
        ->where('barangay_id', $user->barangay_id);

    // 🔎 Monthly filter only
    if ($month) {
        $carbonMonth = \Carbon\Carbon::parse($month);
        $query->whereMonth('period_start', $carbonMonth->month)
              ->whereYear('period_start', $carbonMonth->year);
    }

    $reports = $query->latest()->paginate(10);

    return view('secretary.vawc_reports.index', [
        'reports' => $reports,
        'month'   => $month,
    ]);
}
  public function create()
    {
        // Simple lang ang create form, wala nang population counts
        return view('secretary.vawc_reports.create');
    }

    public function store(Request $request)
{
    $user = auth()->user();

    // Validate input
    $request->validate([
        'period_start'         => 'required|date',
        'period_end'           => 'required|date|after_or_equal:period_start',
        'total_clients_served' => 'nullable|integer|min:0',
        'total_cases_received' => 'nullable|integer|min:0',
        'total_cases_acted'    => 'nullable|integer|min:0',
        'cases'                => 'nullable|array',
        'cases.*.nature_of_case' => 'nullable|string|max:255',
        'cases.*.subcategory'     => 'nullable|string|max:255',
        'cases.*.num_victims'     => 'nullable|integer|min:0',
        'cases.*.num_cases'       => 'nullable|integer|min:0',
        'cases.*.ref_cmswdo'      => 'nullable|integer|min:0',
        'cases.*.ref_pnp'         => 'nullable|integer|min:0',
        'cases.*.ref_court'       => 'nullable|integer|min:0',
        'cases.*.ref_hospital'    => 'nullable|integer|min:0',
        'cases.*.ref_others'      => 'nullable|integer|min:0',
        'cases.*.num_applied_bpo' => 'nullable|integer|min:0',
        'cases.*.num_bpo_issued'  => 'nullable|integer|min:0',
        'programs'               => 'nullable|array',
        'programs.*.ppa_type'    => 'nullable|string|max:255',
        'programs.*.title'       => 'nullable|string|max:255',
        'programs.*.remarks'     => 'nullable|string|max:255',
    ]);

    // Create main report
    $vawcReport = VawcReport::create([
        'barangay_id'          => $user->barangay_id,
        'period_start'         => $request->input('period_start'),
        'period_end'           => $request->input('period_end'),
        'total_clients_served' => (int) $request->input('total_clients_served', 0),
        'total_cases_received' => (int) $request->input('total_cases_received', 0),
        'total_cases_acted'    => (int) $request->input('total_cases_acted', 0),
    ]);

    // Save cases (ensure numeric values are default 0)
    foreach ($request->input('cases', []) as $case) {
        $vawcReport->cases()->create([
            'nature_of_case'   => $case['nature_of_case'] ?? null,
            'subcategory'      => $case['subcategory'] ?? null,
            'num_victims'      => (int) ($case['num_victims'] ?? 0),
            'num_cases'        => (int) ($case['num_cases'] ?? 0),
            'ref_cmswdo'       => (int) ($case['ref_cmswdo'] ?? 0),
            'ref_pnp'          => (int) ($case['ref_pnp'] ?? 0),
            'ref_court'        => (int) ($case['ref_court'] ?? 0),
            'ref_hospital'     => (int) ($case['ref_hospital'] ?? 0),
            'ref_others'       => (int) ($case['ref_others'] ?? 0),
            'num_applied_bpo'  => (int) ($case['num_applied_bpo'] ?? 0),
            'num_bpo_issued'   => (int) ($case['num_bpo_issued'] ?? 0),
        ]);
    }

    // Save programs
    foreach ($request->input('programs', []) as $program) {
        $vawcReport->programs()->create([
            'ppa_type' => $program['ppa_type'] ?? null,
            'title'    => $program['title'] ?? null,
            'remarks'  => $program['remarks'] ?? '-',
        ]);
    }

    // Redirect properly to index (no ID needed)
    return redirect()->route('vawc_reports.index')
                     ->with('success', 'VAWC Report created successfully!');
}

    public function show(VawcReport $vawcReport)
    {
        $vawcReport->load(['cases', 'programs']);
        return view('secretary.vawc_reports.show', compact('vawcReport'));
    }
    

    public function edit($id)
    {
        $vawcReport = VawcReport::with(['cases', 'programs'])->findOrFail($id);
        return view('secretary.vawc_reports.edit', compact('vawcReport'));
    }

  public function update(Request $request, $id)
    {
        $vawcReport = VawcReport::findOrFail($id);

        $vawcReport->update([
            'period_start'         => $request->period_start,
            'period_end'           => $request->period_end,
            'total_clients_served' => (int) $request->total_clients_served,
            'total_cases_received' => (int) $request->total_cases_received,
            'total_cases_acted'    => (int) $request->total_cases_acted,
        ]);

        // Delete old cases & programs
        $vawcReport->cases()->delete();
        $vawcReport->programs()->delete();

        // Recreate cases
        foreach ($request->input('cases', []) as $case) {
            $vawcReport->cases()->create(array_merge($case, [
                'num_victims'     => (int) ($case['num_victims'] ?? 0),
                'num_cases'       => (int) ($case['num_cases'] ?? 0),
                'ref_cmswdo'      => (int) ($case['ref_cmswdo'] ?? 0),
                'ref_pnp'         => (int) ($case['ref_pnp'] ?? 0),
                'ref_court'       => (int) ($case['ref_court'] ?? 0),
                'ref_hospital'    => (int) ($case['ref_hospital'] ?? 0),
                'ref_others'      => (int) ($case['ref_others'] ?? 0),
                'num_applied_bpo' => (int) ($case['num_applied_bpo'] ?? 0),
                'num_bpo_issued'  => (int) ($case['num_bpo_issued'] ?? 0),
            ]));
        }

        // Recreate programs with fixed PPA types
        $fixedPPAs = ['Training', 'Advocacy', 'Others'];
        foreach ($request->input('programs', []) as $index => $program) {
            $vawcReport->programs()->create([
                'ppa_type' => $program['ppa_type'] ?? $fixedPPAs[$index] ?? 'Others',
                'title'    => $program['title'] ?? null,
                'remarks'  => $program['remarks'] ?? '-',
            ]);
        }

        return redirect()->route('vawc_reports.index')
                         ->with('success', 'VAWC Report updated successfully.');
    }

    public function destroy(VawcReport $vawcReport)
    {
        $vawcReport->delete();
        return back()->with('success', 'VAWC Report deleted successfully.');
    }

    public function printReport($id)
    {
        // Eager load relationships
        $vawcReport = VawcReport::with([
            'cases',
            'programs',
            'barangay.residents' => fn($q) => $q->where('status', 'Approved')
        ])->findOrFail($id);
    
        $barangay = $vawcReport->barangay;
        $residents = $barangay->residents;
    
        // Population counts
        $vawcReport->total_population = $residents->count();
        $vawcReport->no_males         = $residents->where('gender', 'Male')->count();
        $vawcReport->no_females       = $residents->where('gender', 'Female')->count();
        $vawcReport->no_adults        = $residents->filter(fn($r) => $r->birthdate <= now()->subYears(18))->count();
        $vawcReport->no_minors        = $residents->filter(fn($r) => $r->birthdate > now()->subYears(18))->count();
    
        // Desk officer and chairperson
        $deskOfficer = BarangayOfficial::where('barangay_id', $barangay->id)
            ->where('position', 'VAW Desk Officer')
            ->first();
    
        $chairperson = User::where('barangay_id', $barangay->id)
            ->where('role', 'Brgy_captain')
            ->first();
    
        // Compute subcategory totals dynamically
        $subcategories = ['Physical', 'Sexual', 'Psychological', 'Economic'];
        foreach ($subcategories as $sub) {
            $vawcReport->{'vawc_' . strtolower($sub)} = $vawcReport->cases
                ->where('subcategory', $sub)
                ->sum('num_victims');
        }
    
        // Referral totals
        $refFields = ['ref_cmswdo', 'ref_pnp', 'ref_court', 'ref_hospital', 'ref_others'];
        foreach ($refFields as $field) {
            $vawcReport->{'vawc_' . str_replace('ref_', '', $field)} = $vawcReport->cases->sum($field);
        }
    
        // BPO totals
        $vawcReport->vawc_bpo_applied = $vawcReport->cases->sum('num_applied_bpo');
        $vawcReport->vawc_bpo_issued  = $vawcReport->cases->sum('num_bpo_issued');
    
        // Make sure cases are sorted for printing
        $vawcReport->cases = $vawcReport->cases->sortBy('id');
    
        return view('secretary.vawc_reports.vawc_print', compact(
            'vawcReport',
            'deskOfficer',
            'chairperson'
        ));
    }
    
    
}    
