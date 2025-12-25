@extends('layouts.app')
@section('title', 'BRCO Reports ')
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
            @include('partials.secretary-header', ['title' => '📄 Document Requests'])
        </div>

        {{-- Page Content --}}
        <div class="p-6 overflow-y-auto flex-1">
            
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
  <div class="p-6 flex-1">
    {{-- Header --}}
    <h2 class="text-2xl font-bold text-blue-900 mb-6">BRCO Reports</h2>

    {{-- Filter + Add Button --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        {{-- Date Filter --}}
        <form id="dateFilterForm" method="GET" action="{{ route('secretary.brco.index') }}" class="flex gap-2 items-center">
          <label for="from_date" class="text-sm">From:</label>
<input type="date" name="from_date" id="from_date" 
       value="{{ request('from_date') }}" 
       class="border p-1 rounded">

<label for="to_date" class="text-sm">To:</label>
<input type="date" name="to_date" id="to_date" 
       value="{{ request('to_date') }}" 
       class="border p-1 rounded">

            <a href="{{ route('secretary.brco.index') }}" id="resetFilter" 
               class="bg-red-500 text-white px-3 py-1 rounded hover:bg-gray-600 {{ (request('from_date') || request('to_date')) ? '' : 'hidden' }}">
               Clear
            </a>

            <a href="{{ route('secretary.brco.print', request()->only(['from_date','to_date'])) }}" 
   id="printButton"
   target="_blank"
   class="bg-green-700 text-white px-3 py-1 rounded hover:bg-gray-800 flex items-center justify-center {{ (request('from_date') && request('to_date')) ? '' : 'hidden' }}">
   <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 14h12v8H6v-8z" />
            </svg>
          Print
</a>

        </form>

        {{-- Add Report Button --}}
        <button id="openCreateModal" 
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            + Add Report
        </button>
    </div>
</div>


<script>
    const fromInput = document.getElementById('from_date');
    const toInput = document.getElementById('to_date');
    const form = document.getElementById('dateFilterForm');
    const resetBtn = document.getElementById('resetFilter');
    const printBtn = document.getElementById('printButton');

    function handleFilterChange() {
        // Show/hide Clear button if any input has value
        if(fromInput.value || toInput.value){
            resetBtn.classList.remove('hidden');
        } else {
            resetBtn.classList.add('hidden');
        }

        // Show Print button only if BOTH dates are filled
        if(fromInput.value && toInput.value){
            printBtn.classList.remove('hidden');
            form.submit(); // auto-submit when both dates are selected
        } else {
            printBtn.classList.add('hidden');
        }
    }

    fromInput.addEventListener('change', handleFilterChange);
    toInput.addEventListener('change', handleFilterChange);
</script>


    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full table-auto border text-sm">
            <thead class="bg-blue-600 text-white">
            <tr>
                <th class="border p-2 w-12 text-center">#</th>
                <th class="border p-2">Location</th>
                <th class="border p-2">Length</th>
                <th class="border p-2">Date</th>
                <th class="border p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $report)
           <tr class="{{ $loop->even ? 'bg-gray-100' : 'bg-white' }} hover:bg-gray-200">
               <td class="border px-3 py-2 text-center">
                {{ $reports->firstItem() ? $reports->firstItem() + $loop->index : $loop->iteration }}
            </td>

                <td class="border p-2">{{ $report->location }}</td>
                <td class="border p-2">{{ $report->length }}</td>
              <td class="border p-2">
    {{ \Carbon\Carbon::parse($report->date)->format('F d, Y') }}
</td>

                <td class="border p-2 text-center">
                <button onclick="openViewModal({{ $report->id }})" 
      class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-md transition"
        title="View">
    <!-- Eye Icon -->
    <svg xmlns="http://www.w3.org/2000/svg"
             fill="none" viewBox="0 0 24 24"
             stroke-width="1.5" stroke="currentColor"
             class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M2.25 12s3.75-6.75 9.75-6.75
                     9.75 6.75 9.75 6.75-3.75 6.75-9.75
                     6.75S2.25 12 2.25 12z" />
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
</button>

<button onclick="openEditModal({{ $report->id }})" 
        class="bg-yellow-100 hover:bg-yellow-200 text-yellow-600 p-2 rounded-lg"
        title="Edit">
    <!-- Pencil Icon -->
    <svg xmlns="http://www.w3.org/2000/svg"
             fill="none" viewBox="0 0 24 24"
             stroke-width="1.5" stroke="currentColor"
             class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
        </svg>
</button>

                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="border p-2 text-center text-gray-500">No reports found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">
        {{ $reports->links() }}
    </div>
</div>

{{-- Hidden Report Details --}}
@foreach($reports as $report)
<div id="report-{{ $report->id }}" class="hidden">
    <table class="w-full border-collapse border border-gray-500 text-sm text-center">
        <thead>
            <tr>
                <th class="border p-2">Conducted</th>
                <th class="border p-2">Location</th>
                <th class="border p-2">Length</th>
                <th class="border p-2">Date</th>
                <th class="border p-2">Action Taken</th>
                <th class="border p-2">Remarks</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="border p-2">{{ $report->conducted ? '✔ Yes' : '✘ No' }}</td>
                <td class="border p-2">{{ $report->location }}</td>
                <td class="border p-2">{{ $report->length }}</td>
                <td class="border p-2">{{ \Carbon\Carbon::parse($report->date)->format('M d, Y') }}</td>
                <td class="border p-2">{{ $report->action_taken }}</td>
                <td class="border p-2">{{ $report->remarks ?? '-' }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Photos --}}
    <div class="mt-4">
        <h4 class="font-bold mb-2">Attached Photos</h4>
        <div class="flex gap-2 flex-wrap">
            @forelse($report->photos as $photo)
            <div class="flex flex-col gap-1 items-center">
                <img src="{{ asset('storage/'.$photo->path) }}" class="w-32 h-32 object-cover rounded border">
                @if(!empty($photo->caption))
                <span class="text-sm text-gray-600">{{ $photo->caption }}</span>
                @endif
            </div>
            @empty
            <p class="text-gray-500">No photos uploaded.</p>
            @endforelse
        </div>
    </div>
</div>
@endforeach

{{-- View Modal --}}
<div id="viewModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg w-2/3 max-h-[90vh] overflow-y-auto">
        <h3 class="text-lg font-bold mb-4">Report Details</h3>
        <div id="viewContent"></div>
        <div class="mt-4 flex justify-end">
            <button onclick="document.getElementById('viewModal').classList.add('hidden')" 
                    class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                Close
            </button>
        </div>
    </div>
</div>
<div id="createModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-2xl shadow-lg w-full max-w-3xl max-h-screen overflow-y-auto">
        <!-- Header -->
        <h3 class="text-2xl font-semibold mb-4 text-gray-800">Add New BRCO Report</h3>

        <!-- Close Button -->
        <button onclick="document.getElementById('createModal').classList.add('hidden')"
                class="absolute top-3 right-3 text-gray-500 hover:text-black text-2xl font-bold" aria-label="Close">
            &times;
        </button>

        <!-- Form -->
        <form id="createForm" action="{{ route('secretary.brco.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Location</label>
                    <input type="text" name="location" class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Length</label>
                    <input type="text" name="length" class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                </div>
               <div>
    <label class="block text-sm font-medium mb-1">Date</label>
    <input 
        type="date" 
        name="date" 
        class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" 
        required
        id="dateInput"
        max="{{ now()->format('Y-m-d') }}"
        title="Cannot select a future date"
    >
</div>

                <div>
                    <label class="block text-sm font-medium mb-1">Action Taken</label>
                    <input type="text" name="action_taken" class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Remarks</label>
                    <input type="text" name="remarks" class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Conducted</label>
                    <select name="conducted" class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                        <option value="" disabled selected>Select</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium mb-2">Photos + Caption</label>
                    <input type="file" name="photos[]" multiple class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" id="photosInputCreate">
                    <div id="previewContainerCreate" class="flex gap-2 flex-wrap mt-2"></div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-4">
                <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" 
                        class="px-6 py-3 bg-gray-500 text-white font-medium rounded-lg hover:bg-gray-600 transition-colors">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-2xl shadow-lg w-full max-w-3xl max-h-screen overflow-y-auto">
        <!-- Header -->
        <h3 class="text-2xl font-semibold mb-4 text-gray-800">Edit Report</h3>

        <!-- Close Button -->
        <button onclick="document.getElementById('editModal').classList.add('hidden')"
                class="absolute top-3 right-3 text-gray-500 hover:text-black text-2xl font-bold" aria-label="Close">
            &times;
        </button>

        <!-- Form -->
        <form id="editForm" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Location</label>
                    <input type="text" name="location" id="edit_location" 
                           class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Length</label>
                    <input type="text" name="length" id="edit_length" 
                           class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                </div>
            <div>
    <label class="block text-sm font-medium mb-1">Date</label>
    <input 
        type="date" 
        name="date" 
        id="edit_date"
        class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" 
        required
        max="{{ now()->format('Y-m-d') }}"
        title="Cannot select a future date"
    >
</div>
                <div>
                    <label class="block text-sm font-medium mb-1">Action Taken</label>
                    <input type="text" name="action_taken" id="edit_action_taken" 
                           class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Remarks</label>
                    <input type="text" name="remarks" id="edit_remarks" 
                           class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Conducted</label>
                    <select name="conducted" id="edit_conducted" 
                            class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <!-- Existing Photos -->
                <div class="col-span-2 mt-2">
                    <h4 class="font-bold mb-1">Existing Photos</h4>
                    <div id="edit_existingPhotos" class="flex gap-2 flex-wrap"></div>
                </div>

                <!-- Add / Replace Photos -->
                <div class="col-span-2 mt-2">
                    <label class="block text-sm font-medium mb-1">Add / Replace Photos</label>
                    <input type="file" name="photos[]" multiple 
                           class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                           id="edit_photosInput">
                    <div id="edit_previewContainer" class="flex gap-2 flex-wrap mt-2"></div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-4">
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" 
                        class="px-6 py-3 bg-gray-500 text-white font-medium rounded-lg hover:bg-gray-600 transition-colors">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const reports = @json($reports->keyBy('id'));

    function openViewModal(reportId){
        const hiddenContent = document.getElementById(`report-${reportId}`).innerHTML;
        document.getElementById('viewContent').innerHTML = hiddenContent;
        document.getElementById('viewModal').classList.remove('hidden');
    }


function openEditModal(reportId) {
    const data = reports[reportId];

    document.getElementById('edit_location').value = data.location;
    document.getElementById('edit_length').value = data.length;
    document.getElementById('edit_date').value = data.date;
    document.getElementById('edit_action_taken').value = data.action_taken;
    document.getElementById('edit_remarks').value = data.remarks ?? '';
    document.getElementById('edit_conducted').value = data.conducted ? '1' : '0';
    document.getElementById('editForm').action = `/secretary/brco/${reportId}`;

    // Populate existing photos
    const container = document.getElementById('edit_existingPhotos');
    container.innerHTML = '';

    data.photos.forEach(photo => {
        const div = document.createElement('div');
        div.classList.add('flex','flex-col','gap-1','items-center');

        const img = document.createElement('img');
        img.src = `/storage/${photo.path}`;
        img.classList.add('w-24','h-24','object-cover','rounded','border');

        const input = document.createElement('input');
        input.type = 'text';
        input.name = `photo_captions[${photo.id}]`;
        input.value = photo.caption ?? '';
        input.classList.add('border','p-1','rounded','w-24','text-sm');

        // DELETE checkbox
        const delLabel = document.createElement('label');
        delLabel.classList.add('flex','items-center','gap-1','text-red-600','text-xs');
        const delCheckbox = document.createElement('input');
        delCheckbox.type = 'checkbox';
        delCheckbox.name = `delete_photos[${photo.id}]`;
        delCheckbox.value = 1; // MUST have value
        delLabel.appendChild(delCheckbox);
        delLabel.appendChild(document.createTextNode('Delete'));

        div.appendChild(img);
        div.appendChild(input);
        div.appendChild(delLabel);

        container.appendChild(div);
    });

    document.getElementById('editModal').classList.remove('hidden');
}

// Preview new photos
const editPhotosInput = document.getElementById('edit_photosInput');
const editPreviewContainer = document.getElementById('edit_previewContainer');
editPhotosInput.addEventListener('change', function(e){
    editPreviewContainer.innerHTML = '';
    Array.from(e.target.files).forEach(file=>{
        const reader = new FileReader();
        reader.onload = function(event){
            const img = document.createElement('img');
            img.src = event.target.result;
            img.classList.add('w-16','h-16','object-cover','rounded','border');
            editPreviewContainer.appendChild(img);
        }
        reader.readAsDataURL(file);
    });
});
 // Open Create Modal
 document.getElementById('openCreateModal').addEventListener('click', () => {
        document.getElementById('createModal').classList.remove('hidden');
    });

    // Preview photos in create modal
    const photosInputCreate = document.getElementById('photosInputCreate');
    const previewContainerCreate = document.getElementById('previewContainerCreate');

    photosInputCreate.addEventListener('change', function (e) {
        previewContainerCreate.innerHTML = '';
        Array.from(e.target.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(event) {
                const div = document.createElement('div');
                div.classList.add('flex','flex-col','items-center','relative');

                const img = document.createElement('img');
                img.src = event.target.result;
                img.classList.add('w-16','h-16','object-cover','rounded','border','mb-1');

                const caption = document.createElement('input');
                caption.type = 'text';
                caption.name = `photo_captions[]`;
                caption.placeholder = 'Caption';
                caption.classList.add('border','p-1','rounded','text-sm','w-24');

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.textContent = 'Remove';
                removeBtn.classList.add('text-xs','text-red-600','mt-1','hover:underline');
                removeBtn.addEventListener('click', () => div.remove());

                div.appendChild(img);
                div.appendChild(caption);
                div.appendChild(removeBtn);
                previewContainerCreate.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    });
     document.addEventListener('DOMContentLoaded', () => {
    const today = new Date().toISOString().split('T')[0];

    // Add Report modal
    const dateInput = document.getElementById('dateInput');
    if (dateInput) {
        dateInput.setAttribute('max', today);
        dateInput.addEventListener('input', () => {
            if (dateInput.value > today) dateInput.value = today;
        });
    }

    // Edit Report modal
    const editDateInput = document.getElementById('edit_date');
    if (editDateInput) {
        editDateInput.setAttribute('max', today);
        editDateInput.addEventListener('input', () => {
            if (editDateInput.value > today) editDateInput.value = today;
        });
    }

    // Filter inputs
    const fromDate = document.getElementById('from_date');
    const toDate = document.getElementById('to_date');
    if (fromDate && toDate) {
        fromDate.setAttribute('max', today);
        toDate.setAttribute('max', today);

        fromDate.addEventListener('input', () => {
            if (fromDate.value > today) fromDate.value = today;
            if (toDate.value && toDate.value < fromDate.value) toDate.value = fromDate.value;
            toDate.setAttribute('min', fromDate.value);
        });

        toDate.addEventListener('input', () => {
            if (toDate.value > today) toDate.value = today;
        });
    }
});

</script>
@endsection
