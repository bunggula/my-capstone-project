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
            @include('partials.secretary-header', ['title' => '📄 Pending Document Requests'])
        </div>

        {{-- Page Content --}}
        <div class="p-6 overflow-y-auto flex-1">
            <h2 class="text-lg font-semibold mb-4">📂 Pending Requests in Your Barangay</h2>

            @if($approvedDocuments->isEmpty())
                <div class="bg-white p-6 rounded shadow text-gray-600">
                    No approved documents found.
                </div>
            @else
            <div class="bg-white shadow rounded-lg p-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-300 text-gray-700 font-semibold">
                            <tr>
                                <th class="px-4 py-3">#</th>
                                <th class="px-4 py-3">Reference Code</th>
                                <th class="px-4 py-3">Resident Name</th>
                                <th class="px-4 py-3">Document Type</th>
                               
                                <th class="px-4 py-3">Approved At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($approvedDocuments as $index => $doc)
                                <tr class="{{ $loop->odd ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-100 transition">
                                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2">{{ $doc->reference_code ?? 'N/A' }}</td>
                                    <td class="px-4 py-2">{{ $doc->resident->full_name ?? 'N/A' }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $doc->document_type }}</td>
                              
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $doc->updated_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </main>
    </div>
    </div>
</div>
@endsection
