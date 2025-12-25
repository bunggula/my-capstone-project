<?php

namespace App\Http\Controllers\ABC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Resident;
use App\Models\Proposal;

class AbcDashboardController extends Controller
{
    public function index()
    {
        // Count only approved residents
        $totalApprovedResidents = Resident::where('approved', true)->count();

        // ✅ Count all proposals (regardless of status)
        $totalProposals = Proposal::count();

        return view('abc.dashboard', compact(
            'totalApprovedResidents',
            'totalProposals'
        ));
    }
}
