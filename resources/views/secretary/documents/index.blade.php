@extends('layouts.app')
@section('title', 'Manage Documents ')
@section('content')
<div class="flex h-screen bg-gray-100">

    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow flex-shrink-0 overflow-auto">
        @include('partials.secretary-sidebar')
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- Header -->
        {{-- Header --}}
        <div class="sticky top-0 z-10 bg-white shadow">
            @include('partials.secretary-header', ['title' => '📄 Document Requests'])
        </div>

        <!-- Scrollable Content -->
        <main class="flex-1 overflow-auto p-6">

            {{-- Flash Messages --}}
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

            <!-- Page Title -->
            <div class="mb-4">
                <h2 class="text-2xl font-bold text-blue-900 mb-6">Manage Documents</h2>
            </div>

            <!-- Total Documents Card + Add Button -->
            <div class="flex justify-between items-center mb-6">
                <!-- Total Documents Card -->
                <div class="p-4 rounded shadow border-l-4 bg-blue-100 border-blue-500 text-blue-700 w-64">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-semibold text-sm">Total Documents</h4>
                            <p class="text-2xl font-bold">{{ $documents->total() }}</p>
                        </div>
                        <svg class="h-6 w-6 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v18H3V3z" />
                        </svg>
                    </div>
                </div>

                <!-- Add Document Button -->
                <button onclick="openAddModal()" 
                    class="bg-indigo-600 text-white px-5 py-2 rounded-lg shadow hover:bg-indigo-700 transition">
                    + Add Document
                </button>
            </div>

            <!-- Table Container -->
            <div class="bg-white shadow rounded-lg p-4">
                <div class="overflow-x-auto">
                    <table class="w-full table-auto border text-sm text-left">
                        <thead class="bg-blue-600 text-white uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3">#</th>
                                <th class="px-6 py-3">Document Name</th>
                                <th class="px-6 py-3">Purposes & Prices</th>
                                <th class="px-6 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($documents as $index => $document)
                                <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-gray-100 transition">
                                    <td class="px-6 py-4">{{ $documents->firstItem() + $loop->index }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-700">{{ $document->name }}</td>
                                    <td class="px-6 py-4">
                                        @if($document->purposes->isEmpty())
                                            <span class="text-gray-400 text-sm">No purposes yet</span>
                                        @else
                                            <ul class="list-disc list-inside text-gray-600">
                                                @foreach($document->purposes as $purpose)
                                                    <li>{{ $purpose->purpose }} - ₱{{ number_format($purpose->price, 2) }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-2 items-center">
                                            <!-- Edit Button -->
                                            <button onclick="fetchDocument({{ $document->id }})" 
                                                    class="bg-yellow-100 hover:bg-yellow-200 text-yellow-600 w-9 h-9 flex items-center justify-center rounded-md shadow transition"
                                                    title="Edit Document">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                </svg>
                                            </button>

                                            <!-- Delete Button -->
                                            <button onclick="confirmDelete({{ $document->id }})" 
                                                    class="bg-red-600 hover:bg-red-700 text-white w-9 h-9 flex items-center justify-center rounded-md shadow transition" 
                                                    title="Delete Document">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none"
                                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0a1 1 0 011 1v0a1 1 0 01-1 1H7a1 1 0 01-1-1v0a1 1 0 011-1h10z"/>
                                                </svg>
                                            </button>

                                            <!-- Hidden delete form -->
                                            <form id="delete-form-{{ $document->id }}" action="{{ route('secretary.documents.destroy', $document->id) }}" method="POST" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-6 text-gray-500">No documents found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $documents->links() }}
                </div>
            </div>

        </main>
    </div>
</div>
<!-- Add Modal -->
<div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-xl p-6">
        <h2 class="text-2xl font-bold mb-4">Add Document</h2>
        <form action="{{ route('secretary.documents.store') }}" method="POST">
            @csrf
            <div class="mb-4">
    <label>Document Name</label>
    <select name="name" class="w-full border rounded px-3 py-2 mt-1" required>
        <option value="" disabled selected>Select a document</option>
        <option value="Barangay Clearance">Barangay Clearance</option>
        <option value="Certificate of Indigency">Certificate of Indigency</option>
        <option value="Certificate of Residency">Certificate of Residency</option>
        <option value="Proof of Income">Proof of Income</option>
        <option value="First time Job Seeker">First time Job Seeker</option>
        <option value="Business Clearance">Business Clearance</option>
        <option value="Barangay Certification">Barangay Certification</option>
        <option value="Electrical Clearance">Electrical Clearance</option> 
        <option value="Fencing Permit">Fencing Permit</option>
         <option value="Building Permit">Building Permit</option>
       <option value="Certification of Late Registration">Certification of Late Registration</option>
       <option value="Solo Parent Certification">Solo Parent Certification</option>

    </select>
</div>


            <div class="mb-4">
                <label>Purposes & Prices</label>
                <div id="addPurposesContainer">
                    <div class="flex space-x-2 mb-2 purpose-row">
                        <input type="text" name="purposes[0][name]" placeholder="Purpose" class="w-2/3 border rounded px-3 py-2" required>
                        <input type="number" step="0.01" name="purposes[0][price]" placeholder="Price" class="w-1/3 border rounded px-3 py-2" required>
                        <button type="button" onclick="removeRow(this)" class="bg-red-500 text-white px-2 rounded">x</button>
                    </div>
                </div>
                <button type="button" onclick="addRow('addPurposesContainer')" class="mt-2 bg-green-500 text-white px-3 py-1 rounded">+ Add Purpose</button>
            </div>

            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" onclick="closeAddModal()" class="px-4 py-2 bg-gray-200 rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-xl p-6">
        <h2 class="text-2xl font-bold mb-4">Edit Document</h2>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
    <label>Document Name</label>
    <select name="name" id="editName" class="w-full border rounded px-3 py-2 mt-1" required>
        <option value="Barangay Clearance">Barangay Clearance</option>
        <option value="Certificate of Indigency">Certificate of Indigency</option>
        <option value="Certificate of Residency">Certificate of Residency</option>
        <option value="Proof of Income">Proof of Income</option>
        <option value="First time Job Seeker">First time Job Seeker</option>
        <option value="Business Clearance">Business Clearance</option>
        <option value="Barangay Indigency">Barangay Indigency</option>
        <option value="Barangay Certification">Barangay Certification</option>
        <option value="Electrical Clearance">Electrical Clearance</option> 
        <option value="Fencing Permit">Fencing Permit</option>
         <option value="Building Permit">Building Permit</option>
         <option value="Certification of Late Registration">Certification of Late Registration</option>
         <option value="Solo Parent Certification">Solo Parent Certification</option>

    </select>
</div>


            <div class="mb-4">
                <label>Purposes & Prices</label>
                <div id="editPurposesContainer"></div>
                <button type="button" onclick="addRow('editPurposesContainer')" class="mt-2 bg-green-500 text-white px-3 py-1 rounded">+ Add Purpose</button>
            </div>

            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-200 rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let addIndex = 1;
let editIndex = 0;

function openAddModal() { document.getElementById('addModal').classList.remove('hidden'); }
function closeAddModal() { document.getElementById('addModal').classList.add('hidden'); }

function openEditModal() { document.getElementById('editModal').classList.remove('hidden'); }
function closeEditModal() { document.getElementById('editModal').classList.add('hidden'); }

function addRow(containerId) {
    const container = document.getElementById(containerId);
    let index = containerId === 'addPurposesContainer' ? addIndex : editIndex;
    const row = document.createElement('div');
    row.classList.add('flex','space-x-2','mb-2','purpose-row');
    row.innerHTML = `
        <input type="text" name="purposes[${index}][name]" placeholder="Purpose" class="w-2/3 border rounded px-3 py-2" required>
        <input type="number" step="0.01" name="purposes[${index}][price]" placeholder="Price" class="w-1/3 border rounded px-3 py-2" required>
        <button type="button" onclick="removeRow(this)" class="bg-red-500 text-white px-2 rounded">x</button>
    `;
    container.appendChild(row);
    containerId === 'addPurposesContainer' ? addIndex++ : editIndex++;
}

function removeRow(button) { button.parentElement.remove(); }

function fetchDocument(id) {
    fetch("{{ url('/secretary/documents') }}/" + id + "/edit")
        .then(res => res.json())
        .then(data => {
            document.getElementById('editForm').action = "{{ url('/secretary/documents') }}/" + id;
            document.getElementById('editName').value = data.name;

            const container = document.getElementById('editPurposesContainer');
            container.innerHTML = '';
            editIndex = 0;

            data.purposes.forEach(p => {
                const row = document.createElement('div');
                row.classList.add('flex','space-x-2','mb-2','purpose-row');
                row.innerHTML = `
                    <input type="hidden" name="purposes[${editIndex}][id]" value="${p.id}">
                    <input type="text" name="purposes[${editIndex}][name]" value="${p.purpose}" class="w-2/3 border rounded px-3 py-2" required>
                    <input type="number" name="purposes[${editIndex}][price]" value="${p.price}" step="0.01" class="w-1/3 border rounded px-3 py-2" required>
                    <button type="button" onclick="removeRow(this)" class="bg-red-500 text-white px-2 rounded">x</button>
                `;
                container.appendChild(row);
                editIndex++;
            });

            openEditModal();
        })
        .catch(err => console.error('Error fetching document:', err));
}

// SweetAlert2 delete confirmation
function confirmDelete(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
</script>
@endsection
