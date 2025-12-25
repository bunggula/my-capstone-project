@extends('layouts.app')

@section('content')
<div class="flex h-screen overflow-y-auto bg-white text-gray-800">
    <!-- Sidebar -->
    @include('partials.secretary-sidebar')

    <!-- Main Panel -->
    <div class="flex-1 flex flex-col min-h-screen">
        <!-- Header -->
        @include('partials.secretary-header')

        <!-- Main Content -->
        <div class="p-6 flex-1">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-semibold">Edit VAWC Report</h2>
                <a href="{{ route('vawc_reports.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 text-sm">
                    ← Back to Reports
                </a>
            </div>

            <form action="{{ route('vawc_reports.update', $vawcReport->id) }}" method="POST">
                @csrf
                @method('PUT')

             

                <hr class="my-4">

                {{-- Population Summary --}}
                <h4 class="font-semibold mb-2">Population Summary (Manual Input)</h4>
             

                <hr class="my-4">

                {{-- Summary of Services --}}
                <h4 class="font-semibold mb-2">Summary of Services</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block mb-1 font-medium">Total No. of Clients Served</label>
                        <input type="number" name="total_clients_served" class="w-full border rounded px-2 py-1" min="0" value="{{ old('total_clients_served', $vawcReport->total_clients_served) }}">
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Total No. of Cases Received by the Barangay</label>
                        <input type="number" name="total_cases_received" class="w-full border rounded px-2 py-1" min="0" value="{{ old('total_cases_received', $vawcReport->total_cases_received) }}">
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Total No. of Cases Acted Upon</label>
                        <input type="number" name="total_cases_acted" class="w-full border rounded px-2 py-1" min="0" value="{{ old('total_cases_acted', $vawcReport->total_cases_acted) }}">
                    </div>
                </div>

                <hr class="my-4">

                {{-- Cases Section --}}
                <h4 class="font-semibold mb-2">Cases</h4>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto border text-sm" id="casesTable">
                        <thead class="bg-blue-600 text-white">
                            <tr>
                                <th class="border px-2 py-1">Nature of Case</th>
                                <th class="border px-2 py-1">Subcategory</th>
                                <th class="border px-2 py-1">Victims</th>
                                <th class="border px-2 py-1">Cases</th>
                                <th class="border px-2 py-1">PNP</th>
                                <th class="border px-2 py-1">Court</th>
                                <th class="border px-2 py-1">Hospital</th>
                                <th class="border px-2 py-1">BPO Applied</th>
                                <th class="border px-2 py-1">BPO Issued</th>
                                <th class="border px-2 py-1">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vawcReport->cases as $index => $case)
                            <tr>
                                <td><input type="text" name="cases[{{ $index }}][nature_of_case]" class="border rounded px-1 py-1 w-full" value="{{ $case->nature_of_case }}" required></td>
                                <td><input type="text" name="cases[{{ $index }}][subcategory]" class="border rounded px-1 py-1 w-full" value="{{ $case->subcategory }}"></td>
                                <td><input type="number" name="cases[{{ $index }}][num_victims]" class="border rounded px-1 py-1 w-full" value="{{ $case->num_victims }}" min="0"></td>
                                <td><input type="number" name="cases[{{ $index }}][num_cases]" class="border rounded px-1 py-1 w-full" value="{{ $case->num_cases }}" min="0"></td>
                                <td><input type="number" name="cases[{{ $index }}][ref_pnp]" class="border rounded px-1 py-1 w-full" value="{{ $case->ref_pnp }}" min="0"></td>
                                <td><input type="number" name="cases[{{ $index }}][ref_court]" class="border rounded px-1 py-1 w-full" value="{{ $case->ref_court }}" min="0"></td>
                                <td><input type="number" name="cases[{{ $index }}][ref_hospital]" class="border rounded px-1 py-1 w-full" value="{{ $case->ref_hospital }}" min="0"></td>
                                <td><input type="number" name="cases[{{ $index }}][num_applied_bpo]" class="border rounded px-1 py-1 w-full" value="{{ $case->num_applied_bpo }}" min="0"></td>
                                <td><input type="number" name="cases[{{ $index }}][num_bpo_issued]" class="border rounded px-1 py-1 w-full" value="{{ $case->num_bpo_issued }}" min="0"></td>
                                <td><button type="button" class="px-2 py-1 bg-red-500 text-white rounded text-xs hover:bg-red-600 remove-case">X</button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="button" id="addCase" class="mt-2 px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">+ Add Case</button>

                <hr class="my-4">

                {{-- Programs Section --}}
                <h4 class="font-semibold mb-2">Programs / Projects / Activities (PPAs)</h4>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto border text-sm" id="programsTable">
                        <thead class="bg-blue-600 text-white">
                            <tr>
                                <th class="border px-2 py-1">PPA Type</th>
                                <th class="border px-2 py-1">Title</th>
                                <th class="border px-2 py-1">Remarks</th>
                                <th class="border px-2 py-1">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vawcReport->programs as $pIndex => $program)
                            <tr>
                                <td>
                                    <select name="programs[{{ $pIndex }}][ppa_type]" class="border rounded px-1 py-1 w-full">
                                        <option value="Training" {{ $program->ppa_type == 'Training' ? 'selected' : '' }}>Training</option>
                                        <option value="Advocacy" {{ $program->ppa_type == 'Advocacy' ? 'selected' : '' }}>Advocacy</option>
                                        <option value="Others" {{ $program->ppa_type == 'Others' ? 'selected' : '' }}>Others</option>
                                    </select>
                                </td>
                                <td><input type="text" name="programs[{{ $pIndex }}][title]" class="border rounded px-1 py-1 w-full" value="{{ $program->title }}"></td>
                                <td><input type="text" name="programs[{{ $pIndex }}][remarks]" class="border rounded px-1 py-1 w-full" value="{{ $program->remarks }}"></td>
                                <td><button type="button" class="px-2 py-1 bg-red-500 text-white rounded text-xs hover:bg-red-600 remove-program">X</button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="button" id="addProgram" class="mt-2 px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">+ Add Program</button>

                <hr class="my-4">

              

                <button type="submit" class="mt-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Update Report</button>
            </form>
        </div>
    </div>
</div>
@endsection

