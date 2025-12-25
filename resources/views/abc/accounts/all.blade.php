@extends('layouts.app')

@section('title', 'All Barangay Officials')

@section('content')
<div class="flex h-screen bg-gray-100 overflow-hidden">
    @include('partials.abc-sidebar')

    <main class="flex-1 flex flex-col overflow-hidden">
        @include('partials.abc-header', ['title' => 'All Barangay Officials'])
        

        <div class="flex-1 overflow-y-auto p-6">
        <div x-data="{
    barangay: '{{ $selectedBarangay }}',
    position: '{{ $positionFilter }}',
    update() {
        const params = new URLSearchParams();
        if(this.barangay) params.set('barangay', this.barangay);
        if(this.position) params.set('position', this.position);
        window.location.href = '{{ route('abc.all.index') }}?' + params.toString();
    },
    clear() {
        this.barangay = '';
        this.position = '';
        window.location.href = '{{ route('abc.all.index') }}';
    },
    print() {
        window.print();
    }
}" class="mb-4 flex items-center space-x-4">

    <label for="barangay" class="font-semibold">Filter by Barangay:</label>
    <select id="barangay" x-model="barangay" @change="update()" class="border px-3 py-2 rounded">
        <option value="">All Barangays</option>
        @foreach($allBarangays as $b)
            <option value="{{ $b->id }}">{{ $b->name }}</option>
        @endforeach
    </select>

    <!-- Clear button only shows if there is a selected filter -->
    <button 
        type="button" 
        x-show="barangay || position" 
        @click="clear()" 
        class="bg-gray-500 text-white px-3 py-2 rounded hover:bg-gray-600"
    >
        Clear
    </button>

    <!-- Print button always visible -->
    <a href="{{ route('abc.all.print', ['barangay' => $selectedBarangay, 'position' => $positionFilter]) }}" 
   target="_blank"
   class="bg-green-600 text-white px-3 py-2 rounded hover:bg-green-700">
    Print
</a>

</div>

            <div class="max-w-7xl mx-auto p-6 bg-white rounded shadow">
                @foreach($barangays as $barangay)
                    <h2 class="mt-6 mb-2 text-xl font-bold">{{ $barangay->name }}</h2>

                    <div class="overflow-x-auto">
                        <table class="w-full table-auto border text-sm text-left">
                            <thead class="bg-blue-600 text-white uppercase text-xs">
                                <tr>
                                    <th class="px-4 py-2">Full Name</th>
                                    <th class="px-4 py-2">Position</th>
                                    <th class="px-4 py-2">Start Year</th>
                                    <th class="px-4 py-2">End Year</th>
                                    <th class="px-4 py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                                {{-- Captain --}}
                                @php $captain = $barangay->users->firstWhere('role', 'brgy_captain'); @endphp
                                @if($captain)
                                    <tr x-data="{ showViewCaptain{{ $captain->id }}: false }" class="bg-blue-50">
                                        <td class="border px-4 py-2 font-semibold">
                                            {{ $captain->last_name }}, {{ $captain->first_name }} {{ $captain->middle_name ?? '' }}
                                        </td>
                                        <td class="border px-4 py-2">Barangay Captain</td>
                                        <td class="border px-4 py-2">{{ $captain->start_year ?? '-' }}</td>
                                        <td class="border px-4 py-2">{{ $captain->end_year ?? '-' }}</td>
                                        <td class="border px-4 py-2">
                                            <button @click="showViewCaptain{{ $captain->id }} = true" class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-md">
                                                View
                                            </button>
                                            {{-- Modal --}}
                                            <div x-show="showViewCaptain{{ $captain->id }}" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                                <div @click.away="showViewCaptain{{ $captain->id }} = false" class="bg-white rounded-3xl w-full max-w-lg p-8 shadow-2xl overflow-y-auto max-h-[90vh]">
                                                    <h2 class="text-xl font-bold mb-4">Barangay Captain Details</h2>
                                                    <p><strong>Full Name:</strong> {{ $captain->first_name }} {{ $captain->middle_name ?? '' }} {{ $captain->last_name }}</p>
                                                    <p><strong>Email:</strong> {{ $captain->email ?? '-' }}</p>
                                                    <p><strong>Gender:</strong> {{ ucfirst($captain->gender) }}</p>
                                                    <p><strong>Start Year:</strong> {{ $captain->start_year ?? '-' }}</p>
                                                    <p><strong>End Year:</strong> {{ $captain->end_year ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif

                                {{-- Secretary --}}
                                @php $secretary = $barangay->users->firstWhere('role', 'secretary'); @endphp
                                @if($secretary)
                                    <tr x-data="{ showViewSecretary{{ $secretary->id }}: false }" class="bg-green-50">
                                        <td class="border px-4 py-2 font-semibold">
                                            {{ $secretary->last_name }}, {{ $secretary->first_name }} {{ $secretary->middle_name ?? '' }}
                                        </td>
                                        <td class="border px-4 py-2">Barangay Secretary</td>
                                        <td class="border px-4 py-2">{{ $secretary->start_year ?? '-' }}</td>
                                        <td class="border px-4 py-2">{{ $secretary->end_year ?? '-' }}</td>
                                        <td class="border px-4 py-2">
                                            <button @click="showViewSecretary{{ $secretary->id }} = true" class="bg-green-600 hover:bg-green-700 text-white p-2 rounded-md">
                                                View
                                            </button>
                                            {{-- Modal --}}
                                            <div x-show="showViewSecretary{{ $secretary->id }}" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                                <div @click.away="showViewSecretary{{ $secretary->id }} = false" class="bg-white rounded-3xl w-full max-w-lg p-8 shadow-2xl overflow-y-auto max-h-[90vh]">
                                                    <h2 class="text-xl font-bold mb-4">Barangay Secretary Details</h2>
                                                    <p><strong>Full Name:</strong> {{ $secretary->first_name }} {{ $secretary->middle_name ?? '' }} {{ $secretary->last_name }}</p>
                                                    <p><strong>Email:</strong> {{ $secretary->email ?? '-' }}</p>
                                                    <p><strong>Gender:</strong> {{ ucfirst($secretary->gender) }}</p>
                                                    <p><strong>Start Year:</strong> {{ $secretary->start_year ?? '-' }}</p>
                                                    <p><strong>End Year:</strong> {{ $secretary->end_year ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif

                                {{-- Other Officials --}}
                                @foreach($barangay->barangayOfficials as $official)
                                    <tr x-data="{ showViewOfficial{{ $official->id }}: false }" class="bg-white">
                                        <td class="border px-4 py-2">{{ $official->last_name }}, {{ $official->first_name }} {{ $official->middle_name ?? '' }}</td>
                                        <td class="border px-4 py-2">{{ $official->position }}</td>
                                        <td class="border px-4 py-2">{{ $official->start_year ?? '-' }}</td>
                                        <td class="border px-4 py-2">{{ $official->end_year ?? '-' }}</td>
                                        <td class="border px-4 py-2">
                                            <button @click="showViewOfficial{{ $official->id }} = true" class="bg-gray-600 hover:bg-gray-700 text-white p-2 rounded-md">
                                                View
                                            </button>
                                            {{-- Modal --}}
                                            <div x-show="showViewOfficial{{ $official->id }}" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                                <div @click.away="showViewOfficial{{ $official->id }} = false" class="bg-white rounded-3xl w-full max-w-lg p-8 shadow-2xl overflow-y-auto max-h-[90vh]">
                                                    <h2 class="text-xl font-bold mb-4">Official Details</h2>
                                                    <p><strong>Full Name:</strong> {{ $official->first_name }} {{ $official->middle_name ?? '' }} {{ $official->last_name }}</p>
                                                    <p><strong>Position:</strong> {{ $official->position }}</p>
                                                    <p><strong>Email:</strong> {{ $official->email ?? '-' }}</p>
                                                    <p><strong>Start Year:</strong> {{ $official->start_year ?? '-' }}</p>
                                                    <p><strong>End Year:</strong> {{ $official->end_year ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>
        </div>
    </main>
</div>
@endsection
