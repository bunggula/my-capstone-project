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
        <div class="p-6">
            <h1 class="text-2xl font-bold text-red-800 mb-6">❌ Rejected Document Requests</h1>

            @if($rejectedDocuments->isEmpty())
                <div class="bg-white p-6 rounded shadow text-gray-600">
                    No rejected documents found.
                </div>
            @else
            <div class="bg-white shadow rounded-lg p-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-300 text-gray-700 font-semibold">
                            <tr>
                                <th class="px-4 py-3 text-left">#</th>
                                <th class="px-4 py-3 text-left">Reference Code</th>
                                <th class="px-4 py-3 text-left">Resident Name</th>
                                <th class="px-4 py-3 text-left">Document Type</th>
                               
                                <th class="px-4 py-3 text-left">Rejected At</th>
                                <th class="px-4 py-3 text-left">Reason</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @foreach($rejectedDocuments as $index => $doc)
                            <tr class="{{ $loop->odd ? 'bg-white' : 'bg-gray-100' }} border-t">
                                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2">{{ $doc->reference_code ?? 'N/A' }}</td>
                                    <td class="px-4 py-2">{{ $doc->resident->full_name ?? 'N/A' }}</td>
                                    <td class="px-4 py-2">{{ $doc->document_type }}</td>
                                    
                                    <td class="px-4 py-2">{{ $doc->updated_at->format('M d, Y') }}</td>
                                    <td class="px-4 py-2 text-red-700">{{ $doc->rejection_reason ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        </div>
    </div>
</div>
@endsection
