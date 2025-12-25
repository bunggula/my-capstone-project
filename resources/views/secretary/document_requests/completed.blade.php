@extends('layouts.app')

@section('content')
<div class="flex font-sans min-h-screen overflow-hidden">

    {{-- Sidebar --}}
    <div class="w-64 bg-white shadow-lg sticky top-0 h-screen z-20">
        @include('partials.secretary-sidebar')
    </div>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col bg-gray-100 h-screen overflow-hidden">

        {{-- Header --}}
        <div class="sticky top-0 z-10 bg-white shadow">
            @include('partials.secretary-header', ['title' => '✅ Completed Document Requests'])
        </div>

        {{-- Page Content --}}
        <div class="p-6 overflow-y-auto flex-1">
            <h2 class="text-lg font-semibold mb-4">📁 Completed Requests in Your Barangay</h2>

            {{-- Table --}}
            <div class="bg-white shadow rounded-lg p-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-300 text-gray-700 font-semibold">
                            <tr>
                                <th class="px-4 py-3 text-left">#</th>
                                <th class="px-4 py-3 text-left">Reference Code</th>
                                <th class="px-4 py-3 text-left">Resident</th>
                                <th class="px-4 py-3 text-left">Document Type</th>
                                <th class="px-4 py-3 text-left">Pickup Date</th>
                                <th class="px-4 py-3 text-left">Completed At</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($requests as $index => $request)
                                <tr class="{{ $loop->odd ? 'bg-white' : 'bg-gray-100' }} border-t">
                                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2">{{ $request->reference_code ?? 'N/A' }}</td>
                                    <td class="px-4 py-2">{{ $request->resident->full_name ?? 'N/A' }}</td>
                                    <td class="px-4 py-2">{{ $request->document_type }}</td>
                                    <td class="px-4 py-2">
                                        {{ $request->pickup_date ? \Carbon\Carbon::parse($request->pickup_date)->format('M d, Y') : 'Not Set' }}
                                    </td>
                                    <td class="px-4 py-2 text-green-600 font-semibold">
                                        {{ \Carbon\Carbon::parse($request->updated_at)->format('M d, Y h:i A') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                        No completed requests found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
