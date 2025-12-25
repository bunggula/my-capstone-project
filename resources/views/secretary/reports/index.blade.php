@extends('layouts.app')
@section('title', 'Waste Reports ')
@section('content')
<div class="flex h-screen overflow-y-auto bg-gray-100 text-gray-800">
    <!-- Sidebar -->
    @include('partials.secretary-sidebar')

    <!-- Main Panel -->
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('partials.secretary-header')

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto p-6"
        x-data="{
                showAddModal: false,
                showViewModal: false,
                showEditModal: false,
                selectedReport: {},
                openView(report) {
                    this.selectedReport = report;
                    this.showViewModal = true;
                },
                openEdit(report) {
                    this.selectedReport = JSON.parse(JSON.stringify(report));
                    this.showEditModal = true;
                }
            }">

            <!-- Header -->
            <div class="flex-1 flex flex-col min-h-screen">
                <h2 class="text-2xl font-bold text-blue-900 mb-6">Waste Reports</h2>
            

        

            <!-- SweetAlert Messages -->
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
<div class="p-4 w-full text-sm mb-4"
     x-data="{
         type: {{ json_encode(request('type') ?? 'daily') }},
         date: {{ json_encode(request('date')) }},
         start_date: {{ json_encode(request('start_date')) }},
         end_date: {{ json_encode(request('end_date')) }},
         month: {{ json_encode(request('month')) }},
         changeType(newType) {
             this.type = newType;
             if(newType==='daily'){ this.start_date=''; this.end_date=''; this.month=''; }
             if(newType==='weekly'){ this.date=''; this.month=''; }
             if(newType==='monthly'){ this.date=''; this.start_date=''; this.end_date=''; }
         }
     }"
     x-cloak
>
    <div class="flex flex-wrap justify-between items-center gap-4">

        <!-- Left: Filter Form -->
        <form x-ref="filterForm" method="GET" action="{{ route('secretary.reports.index') }}" class="flex flex-wrap items-center gap-2">

            <!-- Date Filter -->
            <div class="flex items-center gap-1">
                <label for="type" class="text-sm font-medium">Date Filter:</label>
                <select id="type" name="type" x-model="type" @change="changeType($event.target.value)"
                        class="border rounded px-2 py-1.5">
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                </select>
            </div>

            <!-- DAILY -->
            <div x-show="type==='daily'" class="flex items-center gap-1 transition-all">
                <label for="date" class="text-sm font-medium">Date:</label>
                <input type="date" id="date" name="date" x-model="date" 
                       @change="$refs.filterForm.submit()"
                       class="border rounded px-2 py-1.5">
            </div>

            <!-- WEEKLY -->
            <div x-show="type==='weekly'" class="flex items-center gap-1 transition-all">
                <label class="text-sm font-medium">Start:</label>
                <input type="date" name="start_date" x-model="start_date" max="{{ date('Y-m-d') }}" 
                       @change="$refs.filterForm.submit()"
                       class="border rounded px-2 py-1.5">
                <label class="text-sm font-medium">End:</label>
                <input type="date" name="end_date" x-model="end_date" max="{{ date('Y-m-d') }}" 
                       @change="$refs.filterForm.submit()"
                       class="border rounded px-2 py-1.5">
            </div>

            <!-- MONTHLY -->
            <div x-show="type==='monthly'" class="flex items-center gap-1 transition-all">
                <label for="month" class="text-sm font-medium">Month:</label>
                <input type="month" id="month" name="month" x-model="month" 
                       @change="$refs.filterForm.submit()"
                       class="border rounded px-2 py-1.5">
            </div>

            <!-- Clear Button -->
            @php
                $hasFilter = false;
                if(request('type') === 'daily' && request('date')) $hasFilter = true;
                if(request('type') === 'weekly' && request('start_date') && request('end_date')) $hasFilter = true;
                if(request('type') === 'monthly' && request('month')) $hasFilter = true;
            @endphp
            @if($hasFilter)
                <a href="{{ route('secretary.reports.index') }}"
                   class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded">
                    Clear
                </a>
            @endif
        </form>

        <!-- Right: Action Buttons -->
        <div class="flex gap-2 items-center">
            <button @click="showAddModal = true"
                    class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow text-sm transition">
                ➕ Add Report
            </button>

            @if($wasteReports->isNotEmpty() && $hasFilter)
                <a href="{{ route('secretary.reports.print', request()->all()) }}" 
                   target="_blank"
                   class="flex items-center gap-2 px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded shadow text-sm transition"
                   title="Print">
                     <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 14h12v8H6v-8z" />
            </svg>
                    Print
                </a>
            @endif
        </div>
    </div>
</div>





            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full table-auto border text-sm">
                    <thead class="bg-blue-600 text-white">
                        <tr>
                            <th class="border p-2 w-12 text-center">#</th>
                            <th class="border px-4 py-2">Date Collected</th>
                            <th class="border px-4 py-2">Biodegradable</th>
                            <th class="border px-4 py-2">Special</th>
                            <th class="border px-4 py-2">Remarks</th>
                            <th class="border px-4 py-2 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($wasteReports as $report)
                           <tr class="{{ $loop->even ? 'bg-gray-100' : 'bg-white' }} hover:bg-gray-200">
                              <td class="border px-3 py-2 text-center">
    {{ $wasteReports->firstItem() + $loop->index }}
</td>

                                <td class="border px-4 py-2">
                                    {{ $report->date_collected ? \Carbon\Carbon::parse($report->date_collected)->format('F d, Y') : '-' }}
                                </td>
                                <td class="border px-4 py-2">{{ $report->biodegradable }} kg</td>
                                <td class="border px-4 py-2">{{ $report->special }} kg</td>
                                <td class="border px-4 py-2">{{ $report->remarks }}</td>
                                <td class="border px-4 py-2 text-center">
                                    <div class="flex justify-center gap-2">
                                        <button @click="openView({{ $report->toJson() }})"
                                                class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded text-xs shadow flex items-center justify-center">
                                            <!-- Eye Icon -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                 stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>

                                        <button @click="openEdit({{ $report->toJson() }})"
                                                class="bg-yellow-100 hover:bg-yellow-200 text-yellow-600 p-2 rounded text-xs shadow flex items-center justify-center">
                                            <!-- Pencil Icon -->
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-gray-500">
                                    No waste reports found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $wasteReports->appends(request()->except('page'))->links() }}
</div>
            </div>

        <!-- ✅ Add Modal -->
<div x-cloak x-show="showAddModal" x-transition class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white w-full max-w-3xl p-6 rounded-2xl shadow-2xl relative">
        <h3 class="text-2xl font-semibold mb-6 text-gray-800">Add Waste Report</h3>

        <form action="{{ route('secretary.reports.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @foreach ([
        'date_collected' => 'Date Collected',
        'biodegradable' => 'Biodegradable (kg)',
        'recyclable' => 'Recyclable (kg)',
        'residual_recyclable' => 'Residual (Recyclable) (kg)',
        'residual_disposal' => 'Residual (Disposal) (kg)',
        'special' => 'Special (kg)'
    ] as $field => $label)
        <div>
            <label class="block text-sm font-medium mb-2">{{ $label }}</label>

            <input 
                type="{{ $field === 'date_collected' ? 'date' : 'number' }}"
                name="{{ $field }}"
                class="w-full border rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none"
                {{ $field === 'date_collected' ? 'max=' . date('Y-m-d') : '' }}
                required
            >
        </div>
    @endforeach

                <!-- Remarks spans both columns -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2">Remarks</label>
                    <textarea name="remarks" rows="3"
                              class="w-full border rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none resize-none"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-4 mt-4">
                <button type="button" @click="showAddModal = false"
                        class="px-6 py-3 bg-gray-400 text-white font-medium rounded-xl hover:bg-gray-500 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="px-6 py-3 bg-green-600 text-white font-medium rounded-xl hover:bg-green-700 transition-colors">
                    Submit
                </button>
            </div>
        </form>

        <button @click="showAddModal = false"
                class="absolute top-4 right-4 text-2xl font-bold text-gray-400 hover:text-red-600">&times;</button>
    </div>
</div>

<!-- ✅ View Modal as Landscape Table -->
<div x-cloak x-show="showViewModal" x-transition class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white w-full max-w-6xl p-6 rounded shadow-lg relative">
        <h3 class="text-lg font-bold mb-4">View Waste Report</h3>

        <template x-if="selectedReport">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-blue-600 text-white text-left">
                        <tr>
                            <th class="px-3 py-2">Date Collected</th>
                            <th class="px-3 py-2">Biodegradable (kg)</th>
                            <th class="px-3 py-2">Recyclable (kg)</th>
                            <th class="px-3 py-2">Residual (Recyclable) (kg)</th>
                            <th class="px-3 py-2">Residual (Disposal) (kg)</th>
                            <th class="px-3 py-2">Special (kg)</th>
                            <th class="px-3 py-2">Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr class="hover:bg-gray-100">
                           <td class="px-3 py-2" x-text="selectedReport.date_collected 
    ? new Date(selectedReport.date_collected).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) 
    : '-'">
</td>

                            <td class="px-3 py-2" x-text="selectedReport.biodegradable || 0"></td>
                            <td class="px-3 py-2" x-text="selectedReport.recyclable || 0"></td>
                            <td class="px-3 py-2" x-text="selectedReport.residual_recyclable || 0"></td>
                            <td class="px-3 py-2" x-text="selectedReport.residual_disposal || 0"></td>
                            <td class="px-3 py-2" x-text="selectedReport.special || 0"></td>
                            <td class="px-3 py-2" x-text="selectedReport.remarks || '-'"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </template>

        <div class="flex justify-end mt-4">
        
        </div>
        <button @click="showViewModal = false" class="absolute top-2 right-3 text-xl font-bold">&times;</button>
    </div>
</div>



            <!-- ✅ Edit Modal -->
            <div x-cloak x-show="showEditModal" x-transition class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                <div class="bg-white w-full max-w-xl p-6 rounded shadow-lg relative">
                    <h3 class="text-lg font-bold mb-4">Edit Waste Report</h3>
                    <form :action="`/secretary/reports/${selectedReport.id}`" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <template x-if="selectedReport">
                            <div>
                                <div class="grid gap-2">
                                    <template x-for="[field, label] in Object.entries({
                                        date_collected: 'Date Collected',
                                        biodegradable: 'Biodegradable (kg)',
                                        recyclable: 'Recyclable (kg)',
                                        residual_recyclable: 'Residual (Recyclable) (kg)',
                                        residual_disposal: 'Residual (Disposal) (kg)',
                                        special: 'Special (kg)'
                                    })">
                                        <div>
                                            <label class="block font-semibold" x-text="label"></label>
                                            <input :type="field === 'date_collected' ? 'date' : 'number'"
                                                :name="field"
                                                x-model="selectedReport[field]"
                                                class="w-full border rounded px-3 py-2"
                                                required>
                                        </div>
                                    </template>
                                </div>
                                <div>
                                    <label class="block font-semibold">Remarks</label>
                                    <textarea name="remarks" x-model="selectedReport.remarks"
                                        class="w-full border rounded px-3 py-2"></textarea>
                                </div>
                            </div>
                        </template>
                        <div class="flex justify-end gap-2 mt-4">
                            <button type="button" @click="showEditModal = false" class="bg-gray-300 px-4 py-2 rounded">
                                Cancel
                            </button>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                                Update
                            </button>
                        </div>
                    </form>
                    <button @click="showEditModal = false" class="absolute top-2 right-3 text-xl font-bold">&times;</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

