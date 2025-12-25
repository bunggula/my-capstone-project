@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-50 text-gray-800">
    <!-- Sidebar -->
    <div class="w-64 bg-white shadow-md h-screen sticky top-0">
        @include('partials.secretary-sidebar')
    </div>

    <!-- Main Panel -->
    <div class="flex-1 flex flex-col overflow-y-auto">
        <!-- Header -->
        @include('partials.secretary-header')
  @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: '{{ session('error') }}',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            });
        </script>
    @endif
    @if ($errors->any())
    <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

        <!-- Main Content -->
        <div class="p-6 flex-1 space-y-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-semibold text-gray-900">Create VAWC Report</h2>
                <a href="{{ route('vawc_reports.index') }}" 
                   class="px-4 py-2 bg-blue-700 text-white rounded hover:bg-gray-300 text-sm shadow-sm">
                   ← Back to Reports
                </a>
            </div>

            <form action="{{ route('vawc_reports.store') }}" method="POST" class="space-y-6">
                @csrf

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block mb-1 font-medium text-gray-700">Period Start</label>
        <input 
            type="date" 
            name="period_start" 
            class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-1 focus:ring-blue-500" 
            required
            max="{{ date('Y-m-d') }}"  <!-- Max date is today -->
        
    </div>
    <div>
        <label class="block mb-1 font-medium text-gray-700">Period End</label>
        <input 
            type="date" 
            name="period_end" 
            class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-1 focus:ring-blue-500" 
            required
            max="{{ date('Y-m-d') }}"  <!-- Max date is today -->
        
    </div>
</div>

                <hr class="border-gray-300">

                {{-- Summary of Cases --}}
                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-800">Summary of Cases Received and Action Taken</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block mb-1 font-medium text-gray-700">Total No. of Clients Served</label>
                            <input type="number" name="total_clients_served" class="w-full border border-gray-300 rounded px-3 py-2" min="0">
                        </div>
                        <div>
                            <label class="block mb-1 font-medium text-gray-700">Total No. of Cases Received by the Barangay</label>
                            <input type="number" name="total_cases_received" class="w-full border border-gray-300 rounded px-3 py-2" min="0">
                        </div>
                        <div>
                            <label class="block mb-1 font-medium text-gray-700">Total No. of Cases Acted Upon</label>
                            <input type="number" name="total_cases_acted" class="w-full border border-gray-300 rounded px-3 py-2" min="0">
                        </div>
                    </div>
                </div>

                <hr class="border-gray-300">

                {{-- Cases Table --}}
                <div class="space-y-2">
                    <h4 class="font-semibold text-gray-800">Total Number of Cases Referred</h4>
                    <div class="overflow-x-auto rounded shadow-sm border border-gray-300">
                        <table class="w-full text-sm border-collapse" id="casesTable">
                            <thead class="bg-blue-600 text-white text-xs uppercase tracking-wide">
                                <tr>
                                    <th class="border px-3 py-2 text-left">Nature of Case</th>
                                    <th class="border px-3 py-2 text-center">Victims</th>
                                    <th class="border px-3 py-2 text-center">Cases</th>
                                    <th class="border px-3 py-2 text-center">CMSWDO</th>
                                    <th class="border px-3 py-2 text-center">PNP</th>
                                    <th class="border px-3 py-2 text-center">Court</th>
                                    <th class="border px-3 py-2 text-center">Hospital</th>
                                    <th class="border px-3 py-2 text-center">Others</th>
                                    <th class="border px-3 py-2 text-center">BPO Applied</th>
                                    <th class="border px-3 py-2 text-center">BPO Issued</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @php
                                    $cases = [
                                        "Violence Against Women and Their Children (RA 9262)",
                                        "Physical",
                                        "Sexual",
                                        "Psychological",
                                        "Economic",
                                        "RAPE (RA 8353)",
                                        "Trafficking in Persons (RA 9208/10364)",
                                        "Sexual Harassment (RA 7877)",
                                    ];
                                @endphp

                                @foreach($cases as $i => $case)
                                <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-gray-100">
                                    <td class="border px-3 py-2">{{ $case }}
                                        <input type="hidden" name="cases[{{ $i }}][nature_of_case]" value="{{ $case }}">
                                    </td>
                                    <td class="border px-3 py-2 text-center"><input type="number" name="cases[{{ $i }}][num_victims]" class="w-full border rounded px-2 py-1" min="0"></td>
                                    <td class="border px-3 py-2 text-center"><input type="number" name="cases[{{ $i }}][num_cases]" class="w-full border rounded px-2 py-1" min="0"></td>
                                    <td class="border px-3 py-2 text-center"><input type="number" name="cases[{{ $i }}][ref_cmswdo]" class="w-full border rounded px-2 py-1" min="0"></td>
                                    <td class="border px-3 py-2 text-center"><input type="number" name="cases[{{ $i }}][ref_pnp]" class="w-full border rounded px-2 py-1" min="0"></td>
                                    <td class="border px-3 py-2 text-center"><input type="number" name="cases[{{ $i }}][ref_court]" class="w-full border rounded px-2 py-1" min="0"></td>
                                    <td class="border px-3 py-2 text-center"><input type="number" name="cases[{{ $i }}][ref_hospital]" class="w-full border rounded px-2 py-1" min="0"></td>
                                    <td class="border px-3 py-2 text-center"><input type="number" name="cases[{{ $i }}][ref_others]" class="w-full border rounded px-2 py-1" min="0"></td>
                                    <td class="border px-3 py-2 text-center"><input type="number" name="cases[{{ $i }}][num_applied_bpo]" class="w-full border rounded px-2 py-1" min="0" @if(!in_array($case, ['Violence Against Women and Their Children (RA 9262)', 'Physical'])) disabled @endif></td>
                                    <td class="border px-3 py-2 text-center"><input type="number" name="cases[{{ $i }}][num_bpo_issued]" class="w-full border rounded px-2 py-1" min="0" @if(!in_array($case, ['Violence Against Women and Their Children (RA 9262)', 'Physical'])) disabled @endif></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                   
                </div>

                <hr class="border-gray-300">
{{-- Programs Table --}}
<div class="space-y-2">
    <h4 class="font-semibold text-gray-800">Programs / Projects / Activities (PPAs)</h4>
    <div class="overflow-x-auto rounded shadow-sm border border-gray-300">
        <table class="w-full text-sm border-collapse" id="programsTable">
            <thead class="bg-blue-600 text-white text-xs uppercase tracking-wide">
                <tr>
                    <th class="border px-3 py-2">PPA Type</th>
                    <th class="border px-3 py-2">Title</th>
                    <th class="border px-3 py-2">Remarks</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                <tr class="hover:bg-gray-50">
                    <td class="border px-3 py-2">
                        Training
                        <input type="hidden" name="programs[0][ppa_type]" value="Training">
                    </td>
                    <td class="border px-3 py-2">
                        <input type="text" name="programs[0][title]" class="w-full border rounded px-2 py-1" required>
                    </td>
                    <td class="border px-3 py-2">
                        <input type="text" name="programs[0][remarks]" class="w-full border rounded px-2 py-1">
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="border px-3 py-2">
                        Advocacy
                        <input type="hidden" name="programs[1][ppa_type]" value="Advocacy">
                    </td>
                    <td class="border px-3 py-2">
                        <input type="text" name="programs[1][title]" class="w-full border rounded px-2 py-1">
                    </td>
                    <td class="border px-3 py-2">
                        <input type="text" name="programs[1][remarks]" class="w-full border rounded px-2 py-1">
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="border px-3 py-2">
                        Others
                        <input type="hidden" name="programs[2][ppa_type]" value="Others">
                    </td>
                    <td class="border px-3 py-2">
                        <input type="text" name="programs[2][title]" class="w-full border rounded px-2 py-1">
                    </td>
                    <td class="border px-3 py-2">
                        <input type="text" name="programs[2][remarks]" class="w-full border rounded px-2 py-1">
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>



                <hr class="border-gray-300">

                <button type="submit" class="mt-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 shadow-sm">Save Report</button>
            </form>
        </div>
    </div>
</div>
@endsection

