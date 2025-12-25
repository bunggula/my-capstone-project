@extends('layouts.app')
@section('title', 'VAWC Reports ')
@section('content')
<div class="flex h-screen overflow-y-auto bg-gray-100 text-gray-800">
    @include('partials.secretary-sidebar')

      <div class="flex-1 flex flex-col overflow-hidden">
        @include('partials.secretary-header')


    <!-- Top Row: Page Title -->
     <div class="flex-1 overflow-y-auto p-6">
        <h2 class="text-2xl font-bold text-blue-900 mb-6">VAWC Reports</h2>


<!-- Bottom Row: Filter + Button -->
<div class="flex justify-between items-center gap-2 mb-4 flex-wrap">

    <!-- Left: Month Filter Form -->
    <form method="GET" action="{{ route('vawc_reports.index') }}" 
          x-data="{ filter: 'monthly' }"
          class="flex gap-2 items-center">

        <!-- Fixed Filter Type -->
        <select name="date_filter" x-model="filter" class="border px-2 py-1" disabled>
            <option value="monthly" selected>Select Month</option>
        </select>

        <!-- Monthly Filter Input -->
        <input type="month" 
               name="month"
               max="{{ now()->format('Y-m') }}"
               value="{{ request('month') }}" 
               @change="$el.form.submit()"
               class="border px-2 py-1">

        <!-- Clear Button: Only show if a month is selected -->
        @if(request('month'))
            <a href="{{ route('vawc_reports.index') }}" 
               class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded text-sm">
                Clear
            </a>
        @endif
    </form>

    <!-- Right: New Report Button -->
    <a href="{{ route('vawc_reports.create') }}" 
       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
       + New Report
    </a>
</div>



    <!-- Success/Error alerts -->
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
            @if($reports->isEmpty())
                <div class="text-gray-600">No reports found.</div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full table-auto border text-sm">
                    <thead class="bg-blue-600 text-white">
                        <tr>
                               <th class="border px-3 py-2 w-12 text-center">#</th>
                            <th class="border px-3 py-2">Period</th>
                            <th class="border px-3 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $index => $report)
                    <tr class="{{ $loop->even ? 'bg-gray-100' : 'bg-white' }} hover:bg-gray-200">
                            <td class="border px-3 py-2 text-center">
            {{ $reports->firstItem() ? $reports->firstItem() + $loop->index : $loop->iteration }}
        </td>
                            <td class="border px-3 py-2">
    {{ $report->period_start ? \Carbon\Carbon::parse($report->period_start)->format('F d, Y') : '-' }}
    - 
    {{ $report->period_end ? \Carbon\Carbon::parse($report->period_end)->format('F d, Y') : '-' }}
</td>

                            <td class="border px-3 py-2 text-center" x-data="{ openView: false, openEdit: false }">
    <div class="flex justify-center items-center space-x-2">
        <!-- View Button -->
        <button @click="openView = true" 
                class="px-2 py-1 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
        </button>

        <!-- Edit Button -->
        <button @click="openEdit = true" 
                class="px-2 py-1 bg-yellow-100 text-yellow-600 rounded text-xs hover:bg-yellow-200 flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
            </svg>
        </button>

        <!-- Print Button -->
        <a href="{{ route('secretary.vawc_reports.vawc_print', $report->id) }}" target="_blank"
           class="px-2 py-1 bg-green-500 text-white rounded text-xs hover:bg-green-600 flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 14h12v8H6v-8z" />
            </svg>
        </a>
    </div>



                             <!-- View Modal (Smaller Version) -->
<div x-show="openView" x-cloak
     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div @click.away="openView = false" 
         class="bg-white rounded-lg shadow-xl w-11/12 md:w-3/5 p-4 max-h-[75vh] overflow-auto relative">
        
        <!-- Close Button -->
        <button @click="openView = false" 
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-lg font-bold">✖</button>

        <!-- Modal Title -->
        <h3 class="text-xl font-bold text-gray-800 mb-3 border-b pb-1">VAWC Report</h3>

        <!-- Period -->
        <p class="text-gray-700 mb-3">
            <span class="font-semibold">Period:</span> 
            {{ $report->period_start ? \Carbon\Carbon::parse($report->period_start)->format('M d, Y') : '-' }} 
            - 
            {{ $report->period_end ? \Carbon\Carbon::parse($report->period_end)->format('M d, Y') : '-' }}
        </p>

        <hr class="my-3 border-gray-300">

        <!-- Summary Section -->
        <div class="mb-3">
            <h4 class="text-md font-semibold text-gray-800 mb-1">Summary of Cases</h4>
            <ul class="list-disc list-inside text-gray-700 text-sm">
                <li>Total Clients Served: {{ $report->total_clients_served ?? 0 }}</li>
                <li>Total Cases Received: {{ $report->total_cases_received ?? 0 }}</li>
                <li>Total Cases Acted Upon: {{ $report->total_cases_acted ?? 0 }}</li>
            </ul>
        </div>

        <hr class="my-3 border-gray-300">

        <!-- Cases Table -->
        <div class="mb-4">
            <h4 class="text-md font-semibold text-gray-800 mb-1">Cases Referred</h4>
            <div class="overflow-x-auto border rounded-lg text-sm">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-blue-600 text-white text-left">
                        <tr>
                            <th class="px-2 py-1">Nature</th>
                            <th class="px-2 py-1">Victims</th>
                            <th class="px-2 py-1">Cases</th>
                            <th class="px-2 py-1">PNP</th>
                            <th class="px-2 py-1">Court</th>
                            <th class="px-2 py-1">Hospital</th>
                            <th class="px-2 py-1">Others</th>
                            <th class="px-2 py-1">BPO Applied</th>
                            <th class="px-2 py-1">BPO Issued</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 text-center">
                        @forelse($report->cases as $case)
                        <tr class="{{ $loop->even ? 'bg-gray-50' : '' }} hover:bg-gray-100">
                            <td class="px-2 py-1 text-left">{{ $case->nature_of_case ?? '-' }}</td>
                            <td class="px-2 py-1">{{ $case->num_victims ?? 0 }}</td>
                            <td class="px-2 py-1">{{ $case->num_cases ?? 0 }}</td>
                            <td class="px-2 py-1">{{ $case->ref_pnp ?? 0 }}</td>
                            <td class="px-2 py-1">{{ $case->ref_court ?? 0 }}</td>
                            <td class="px-2 py-1">{{ $case->ref_hospital ?? 0 }}</td>
                            <td class="px-2 py-1">{{ $case->ref_others ?? 0 }}</td>
                            <td class="px-2 py-1">{{ $case->num_applied_bpo ?? 0 }}</td>
                            <td class="px-2 py-1">{{ $case->num_bpo_issued ?? 0 }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="py-2 text-gray-500">No cases recorded.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
                                        <div class="row">
    @forelse($report->programs as $program)
    <div class="col-md-6 mb-2">
        <div class="border p-2" style="background-color: #fefefe; font-size: 0.88rem;">
            <strong>{{ $program->ppa_type ?? '-' }}</strong>: {{ $program->title ?? '-' }}<br>
            <small class="text-muted">Remarks: {{ $program->remarks ?? '-' }}</small>
        </div>
    </div>
    @empty
    <div class="col-12">
        <p>No programs recorded.</p>
    </div>
    @endforelse
</div>




                                    </div>
                                </div>
                                <!-- End View Modal -->

                                <!-- Edit Modal -->
                                <div x-show="openEdit" x-cloak
                                     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                                    <div @click.away="openEdit = false" 
                                         class="bg-white rounded-lg shadow-lg w-11/12 md:w-4/5 p-6 max-h-[80vh] overflow-auto relative">
                                        <button @click="openEdit = false" 
                                                class="absolute top-2 right-2 text-gray-500 hover:text-gray-800">✖</button>

                                        <h3 class="text-lg font-semibold mb-4">Edit VAWC Report</h3>

                                        <form action="{{ route('vawc_reports.update', $report->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                      <!-- Period (editable) -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
    <div>
        <label>Period Start</label>
        <input type="date" name="period_start" 
               value="{{ old('period_start', \Carbon\Carbon::parse($report->period_start)->format('Y-m-d')) }}" 
               class="border rounded px-2 py-1 w-full">
    </div>
    <div>
        <label>Period End</label>
        <input type="date" name="period_end" 
               value="{{ old('period_end', \Carbon\Carbon::parse($report->period_end)->format('Y-m-d')) }}" 
               class="border rounded px-2 py-1 w-full">
    </div>
</div>


                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                                <div>
                                                    <label>Total Clients Served</label>
                                                    <input type="number" name="total_clients_served" value="{{ $report->total_clients_served }}" class="border rounded px-2 py-1 w-full" min="0">
                                                </div>
                                                <div>
                                                    <label>Total Cases Received</label>
                                                    <input type="number" name="total_cases_received" value="{{ $report->total_cases_received }}" class="border rounded px-2 py-1 w-full" min="0">
                                                </div>
                                                <div>
                                                    <label>Total Cases Acted</label>
                                                    <input type="number" name="total_cases_acted" value="{{ $report->total_cases_acted }}" class="border rounded px-2 py-1 w-full" min="0">
                                                </div>
                                            </div>

                                            <!-- Cases Table -->
                                       <!-- Cases Table -->
<h4 class="font-semibold mt-4 mb-2">Cases</h4>
<div class="overflow-x-auto">
    <table class="w-full table-auto border text-sm" id="casesTable-{{ $report->id }}">
        <thead class="bg-blue-600 text-white">
            <tr>
                <th>Nature of Case</th>
                <th>Victims</th>
                <th>Cases</th>
                <th>PNP</th>
                <th>Court</th>
                <th>Hospital</th>
                <th>Others</th>
                <th>BPO Applied</th>
                <th>BPO Issued</th>
                
            </tr>
        </thead>
        <tbody>
@foreach($report->cases as $cIndex => $case)
<tr>
    <td><input type="text" name="cases[{{ $cIndex }}][nature_of_case]" value="{{ $case->nature_of_case }}" class="border rounded px-1 py-1 w-full bg-gray-100" readonly></td>
    <td><input type="number" name="cases[{{ $cIndex }}][num_victims]" value="{{ $case->num_victims }}" class="border rounded px-1 py-1 w-full" min="0"></td>
    <td><input type="number" name="cases[{{ $cIndex }}][num_cases]" value="{{ $case->num_cases }}" class="border rounded px-1 py-1 w-full" min="0"></td>
    <td><input type="number" name="cases[{{ $cIndex }}][ref_pnp]" value="{{ $case->ref_pnp }}" class="border rounded px-1 py-1 w-full" min="0"></td>
    <td><input type="number" name="cases[{{ $cIndex }}][ref_court]" value="{{ $case->ref_court }}" class="border rounded px-1 py-1 w-full" min="0"></td>
    <td><input type="number" name="cases[{{ $cIndex }}][ref_hospital]" value="{{ $case->ref_hospital }}" class="border rounded px-1 py-1 w-full" min="0"></td>
    <td><input type="number" name="cases[{{ $cIndex }}][ref_others]" value="{{ $case->ref_others }}" class="border rounded px-1 py-1 w-full" min="0"></td>

    <!-- BPO fields: editable lang kung Nature of Case ay RA 9262 o Physical -->
    @php
        $isEditableBPO = in_array($case->nature_of_case, [
            'Violence Against Women and Their Children (RA 9262)',
            'Physical'
        ]);
    @endphp
    <td>
        <input type="number" name="cases[{{ $cIndex }}][num_applied_bpo]" 
               value="{{ $case->num_applied_bpo }}" 
               class="border rounded px-1 py-1 w-full {{ $isEditableBPO ? '' : 'bg-gray-100' }}" 
               min="0" {{ $isEditableBPO ? '' : 'readonly' }}>
    </td>
    <td>
        <input type="number" name="cases[{{ $cIndex }}][num_bpo_issued]" 
               value="{{ $case->num_bpo_issued }}" 
               class="border rounded px-1 py-1 w-full {{ $isEditableBPO ? '' : 'bg-gray-100' }}" 
               min="0" {{ $isEditableBPO ? '' : 'readonly' }}>
    </td>
</tr>
@endforeach
</tbody>

    </table>
</div>

<!-- Programs Table -->
<h4 class="font-semibold mt-4 mb-2">Programs / Projects / Activities</h4>
<div class="overflow-x-auto">
    <table class="w-full table-auto border text-sm" id="programsTable-{{ $report->id }}">
        <thead class="bg-blue-600 text-white">
            <tr>
                <th>PPA Type</th>
                <th>Title</th>
                <th>Remarks</th>
               
            </tr>
        </thead>
        <tbody>
            @foreach($report->programs as $pIndex => $program)
            <tr>
                <td>
                    <select name="programs[{{ $pIndex }}][ppa_type]" class="border rounded px-1 py-1 w-full bg-gray-100" disabled>
                        <option value="Training" {{ $program->ppa_type == 'Training' ? 'selected' : '' }}>Training</option>
                        <option value="Advocacy" {{ $program->ppa_type == 'Advocacy' ? 'selected' : '' }}>Advocacy</option>
                        <option value="Others" {{ $program->ppa_type == 'Others' ? 'selected' : '' }}>Others</option>
                    </select>
                </td>
                <td><input type="text" name="programs[{{ $pIndex }}][title]" value="{{ $program->title }}" class="border rounded px-1 py-1 w-full"></td>
                <td><input type="text" name="programs[{{ $pIndex }}][remarks]" value="{{ $program->remarks }}" class="border rounded px-1 py-1 w-full"></td>
                
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


                                            <button type="submit" class="mt-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Update Report</button>
                                        </form>
                                    </div>
                                </div>
                                <!-- End Edit Modal -->

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $reports->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- AlpineJS Helpers for dynamic rows -->

@endsection
