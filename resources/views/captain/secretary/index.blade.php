@extends('layouts.app')

@section('title', 'Barangay Secretaries')

@section('content')
<div x-data="secretaryManager()" class="flex h-screen overflow-hidden bg-gray-100">
    @include('partials.captain-sidebar')

    <main class="flex-1 flex flex-col overflow-hidden">
        @include('partials.captain-header', ['title' => 'Barangay Secretaries'])

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

        <div class="overflow-x-auto p-8">
            <div class="flex justify-between items-end flex-wrap gap-4 mb-6">
                <h2 class="text-2xl font-bold text-blue-900">Secretaries</h2>
                <button @click="toggleAddModal(true)" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow text-sm">
                    + Add Secretary
                </button>
            </div>

            {{-- Table --}}
            <div class="bg-white shadow rounded-lg p-2">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm border border-gray-200">
                        <thead class="bg-blue-600 text-white text-left">
                            <tr>
                                <th class="px-4 py-3 border-b">#</th>
                                <th class="px-4 py-3 border-b">Name</th>
                                <th class="px-4 py-3 border-b">Email</th>
                                <th class="px-4 py-3 border-b">Gender</th>
                                <th class="px-4 py-3 border-b">Birthday</th>
                                <th class="px-4 py-3 border-b text-center">Status</th>
                                <th class="px-4 py-3 border-b text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($secretaries as $secretary)
                                <tr class="{{ $loop->even ? 'bg-gray-100' : 'bg-white' }} hover:bg-gray-200">
                                    <td class="px-4 py-2">{{ $loop->iteration }}</td>
                                  <td class="px-4 py-2">
    {{ $secretary->first_name }} {{ $secretary->middle_name ? $secretary->middle_name.' ' : '' }}{{ $secretary->last_name }}
</td>

                                    <td class="px-4 py-2">{{ $secretary->email }}</td>
                                    <td class="px-4 py-2 capitalize">{{ $secretary->gender }}</td>
                                    <td class="px-4 py-2">{{ $secretary->birthday ? \Carbon\Carbon::parse($secretary->birthday)->format('M d, Y') : 'N/A' }}</td>
                                    <td class="px-4 py-2 text-center">
                                        @if($secretary->status === 'active')
                                            <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded">Active</span>
                                        @else
                                            <span class="bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-center space-x-2">
                                        {{-- Edit --}}
                                       <button 
    @click="loadEditSecretary(
        {{ $secretary->id }}, 
        '{{ $secretary->first_name }}', 
        '{{ $secretary->middle_name }}', 
        '{{ $secretary->last_name }}', 
        '{{ $secretary->email }}', 
        '{{ $secretary->gender }}', 
        '{{ $secretary->birthday }}'
    )"
    class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-yellow-100 hover:bg-yellow-200 text-yellow-600 transition"
    title="Edit"
>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
                                            </svg>
                                        </button>

                                     <form id="toggle-form-{{ $secretary->id }}" action="{{ route('captain.secretary.toggle', $secretary->id) }}" method="POST" class="inline">
    @csrf
    @method('PATCH') {{-- <- dito --}}
    <button type="button" @click="confirmToggle({{ $secretary->id }}, '{{ $secretary->status }}', '{{ $secretary->name }}')"
        class="inline-flex items-center justify-center w-8 h-8 rounded-md {{ $secretary->status === 'active' ? 'bg-red-100 text-red-600 hover:bg-red-200' : 'bg-green-100 text-green-600 hover:bg-green-200' }} transition"
        title="{{ $secretary->status === 'active' ? 'Deactivate' : 'Activate' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m5.657-4.243a8 8 0 11-11.314 0" />
        </svg>
    </button>
</form>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-gray-500 py-6">No secretaries found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

 {{-- Add Secretary Modal --}}
<div x-show="showAddModal" x-transition class="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center p-4">
    <div class="bg-white w-full max-w-3xl p-6 rounded-xl shadow-2xl overflow-y-auto max-h-[90vh] relative border-t-4 border-blue-600">
        <button @click="toggleAddModal(false)" class="absolute top-4 right-4 text-gray-400 hover:text-gray-800 text-2xl font-bold">&times;</button>
        <h2 class="text-2xl font-bold mb-6 text-blue-700">Add Secretary</h2>

        <form action="{{ route('captain.secretary.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <input type="text" name="first_name" placeholder="First Name" required class="border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <input type="text" name="middle_name" placeholder="Middle Name" class="border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <input type="text" name="last_name" placeholder="Last Name" required class="border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <input type="email" name="email" placeholder="Email" required class="border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <select name="gender" required class="border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
                <input type="date" name="birthday" required class="border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" @click="toggleAddModal(false)" class="px-5 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Add Secretary</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Secretary Modal --}}
<div x-show="showEditModal" x-transition class="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center p-4">
    <div class="bg-white w-full max-w-3xl p-6 rounded-xl shadow-2xl overflow-y-auto max-h-[90vh] relative border-t-4 border-yellow-500">
        <button @click="toggleEditModal(false)" class="absolute top-4 right-4 text-gray-400 hover:text-gray-800 text-2xl font-bold">&times;</button>
        <h2 class="text-2xl font-bold mb-6 text-yellow-600">Edit Secretary</h2>

        <form :action="`/captain/secretary/${editId}`" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <input type="text" name="first_name" placeholder="First Name" x-model="editFirstName" required class="border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                <input type="text" name="middle_name" placeholder="Middle Name" x-model="editMiddleName" class="border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                <input type="text" name="last_name" placeholder="Last Name" x-model="editLastName" required class="border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                <input type="email" name="email" placeholder="Email" x-model="editEmail" required class="border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                <select name="gender" x-model="editGender" required class="border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                 
                </select>
                <input type="date" name="birthday" x-model="editBirthday" required class="border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" @click="toggleEditModal(false)" class="px-5 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">Update</button>
            </div>
        </form>
    </div>
</div>

</div>

<script>
function secretaryManager() {
    return {
        showAddModal: false,
        showEditModal: false,
        editId: null,
        editFirstName: '',
        editMiddleName: '',
        editLastName: '',
        editEmail: '',
        editGender: '',
        editBirthday: '',

        toggleAddModal(value) {
            this.showAddModal = value;
        },

        toggleEditModal(value) {
            this.showEditModal = value;
        },

       loadEditSecretary(id, firstName, middleName, lastName, email, gender, birthday) {
    this.editId = id;
    this.editFirstName = firstName;
    this.editMiddleName = middleName;
    this.editLastName = lastName;
    this.editEmail = email;
    this.editGender = gender;
    this.editBirthday = birthday;
    this.showEditModal = true;
},

        confirmToggle(secretaryId, currentStatus, name) {
            const actionText = currentStatus === 'active' ? 'deactivate' : 'activate';
            Swal.fire({
                title: 'Are you sure?',
                html: `Do you want to <b>${actionText}</b> the account of <b>${name}</b>?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#aaa',
                confirmButtonText: `Yes, ${actionText} it`
            }).then((result) => {
                if(result.isConfirmed){
                    document.getElementById(`toggle-form-${secretaryId}`).submit();
                }
            });
        }
    }
}
</script>
