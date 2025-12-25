<!-- WRAPPER FOR ALPINEJS -->
<div x-data="{
    openSecretaryModal: false,
    viewSecretaryModal: false,
    editSecretaryModal: false,
    selectedSecretary: null,
    editSecretaryData: {
        id: null,
        first_name: '',
        middle_name: '',
        last_name: '',
        email: '',
        gender: '',
        birthday: ''
    }
}">

    <!-- EXISTING BARANGAY FORM -->
    <form action="{{ route('captain.barangay-info.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Barangay Name -->
            <div>
                <label class="font-semibold block mb-1">Barangay Name</label>
                <input type="text" name="name" value="{{ old('name', $barangay->name) }}" class="w-full border px-3 py-2 rounded bg-gray-100" readonly>
            </div>

            <!-- Chairperson -->
            <div>
                <label class="font-semibold block mb-1">Chairperson Name</label>
                <input type="text" name="chairperson" value="{{ old('chairperson', $barangay->chairperson ?? ($chairperson->full_name ?? '')) }}" class="w-full border px-3 py-2 rounded bg-gray-100" readonly>
            </div>

            <!-- Contact Number -->
            <div>
                <label class="font-semibold block mb-1">Contact Number</label>
                <input type="text" name="contact_number" value="{{ old('contact_number', $barangay->contact_number) }}" class="w-full border px-3 py-2 rounded">
            </div>

            <!-- Email -->
            <div>
                <label class="font-semibold block mb-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="w-full border px-3 py-2 rounded bg-gray-100" readonly>
            </div>

            <!-- Office Hours -->
            <div>
                <label class="font-semibold block mb-1">Office Hours</label>
                <input type="text" name="office_hours" value="{{ old('office_hours', $barangay->office_hours) }}" class="w-full border px-3 py-2 rounded">
            </div>
        </div>

        <!-- Logo -->
        <div class="mt-6">
            <label class="font-semibold block mb-1">Barangay Logo</label>
            <input type="file" name="logo" class="w-full">
            @if($barangay->logo)
                <img src="{{ asset('storage/' . $barangay->logo) }}" class="h-20 mt-2">
            @endif
        </div>

        <div class="mt-8 text-right">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">Update Info</button>
        </div>
    </form>

    <!-- ========================== -->
    <!--      SECRETARY SECTION    -->
    <!-- ========================== -->
    <div class="mt-10">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Barangay Secretary</h2>
            @unless($secretary && $secretary->status === 'active')
                <button @click="openSecretaryModal = true" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">+ Add Secretary</button>
            @endunless
        </div>

        @if($secretary)
            <div class="bg-white shadow rounded p-4 flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <div>
                        <p class="font-semibold">{{ $secretary->full_name }}</p>
                        <p class="text-sm text-gray-500">{{ $secretary->email }}</p>
                    </div>
                    <div>
                        @if($secretary->status === 'active')
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded">Active</span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-800 bg-red-100 rounded">Inactive</span>
                        @endif
                    </div>
                </div>

                <div class="flex items-center space-x-2">
                    <!-- View Button -->
                    <button @click="selectedSecretary = {{ $secretary->id }}; viewSecretaryModal = true" title="View Details"  class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-blue-700 text-white hover:bg-blue-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                                            class="w-4 h-4" 
                                            fill="none" 
                                            viewBox="0 0 24 24" 
                                            stroke="currentColor" 
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                    </button>

                    <!-- Edit Button -->
                    <button @click="
                        editSecretaryData = {
                            id: {{ $secretary->id }},
                            first_name: '{{ addslashes($secretary->first_name) }}',
                            middle_name: '{{ addslashes($secretary->middle_name ?? '') }}',
                            last_name: '{{ addslashes($secretary->last_name) }}',
                            email: '{{ addslashes($secretary->email) }}',
                            gender: '{{ $secretary->gender }}',
                            birthday: '{{ $secretary->birthday }}'
                        };
                        editSecretaryModal = true;
                    " title="Edit Secretary"  class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-yellow-100 hover:bg-yellow-200 text-yellow-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg"
         fill="none" viewBox="0 0 24 24"
         stroke-width="1.5" stroke="currentColor"
         class="w-4 h-4">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
    </svg>
                    </button>

                    <form id="toggle-form-{{ $secretary->id }}" action="{{ route('captain.secretary.toggle', $secretary->id) }}" method="POST" class="inline">
    @csrf
    @method('PATCH')
    <button 
        type="button"
        onclick="event.stopPropagation(); confirmToggle({{ $secretary->id }}, '{{ $secretary->status }}', '{{ $secretary->first_name }} {{ $secretary->last_name }}')"
        title="{{ $secretary->status === 'active' ? 'Deactivate Secretary' : 'Activate Secretary' }}"
        class="inline-flex items-center justify-center w-8 h-8 rounded-md {{ $secretary->status === 'active' ? 'bg-red-100 text-red-600 hover:bg-red-200' : 'bg-green-100 text-green-600 hover:bg-green-200' }} transition"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 6v6m5.657-4.243a8 8 0 11-11.314 0" />
        </svg>
    </button>
</form>



                </div>
            </div>
        @else
            <p class="text-gray-500">No secretary assigned yet.</p>
        @endif
    </div>

    <!-- EDIT SECRETARY MODAL -->
    <div x-show="editSecretaryModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" x-cloak>
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
            <h2 class="text-xl font-semibold mb-4">Edit Secretary</h2>

            <form :action="`/captain/secretary/${editSecretaryData.id}`" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block font-semibold mb-1">First Name</label>
                    <input type="text" name="first_name" x-model="editSecretaryData.first_name" class="w-full border px-3 py-2 rounded" required>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold mb-1">Middle Name</label>
                    <input type="text" name="middle_name" x-model="editSecretaryData.middle_name" class="w-full border px-3 py-2 rounded">
                </div>

                <div class="mb-4">
                    <label class="block font-semibold mb-1">Last Name</label>
                    <input type="text" name="last_name" x-model="editSecretaryData.last_name" class="w-full border px-3 py-2 rounded" required>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold mb-1">Email</label>
                    <input type="email" name="email" x-model="editSecretaryData.email" class="w-full border px-3 py-2 rounded" required>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold mb-1">Gender</label>
                    <select name="gender" x-model="editSecretaryData.gender" class="w-full border px-3 py-2 rounded" required>
                        <option value="">Select…</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold mb-1">Birthday</label>
                    <input type="date" name="birthday" x-model="editSecretaryData.birthday" class="w-full border px-3 py-2 rounded" required>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" @click="editSecretaryModal = false" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
                </div>
            </form>
        </div>
    </div>
<!-- VIEW SECRETARY MODAL -->
<div x-show="viewSecretaryModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" x-cloak>
    <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md transform transition-all duration-300 scale-95 x-show.transition.scale.100">
        
        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-semibold text-gray-800">Secretary Details</h2>
            <button @click="viewSecretaryModal = false" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Content -->
        <div class="space-y-3">
            <div class="flex items-center">
                <span class="w-32 font-medium text-gray-600">Name:</span>
                <span class="text-gray-800">{{ $secretary->full_name ?? '-' }}</span>
            </div>
            <div class="flex items-center">
                <span class="w-32 font-medium text-gray-600">Email:</span>
                <span class="text-gray-800">{{ $secretary->email ?? '-' }}</span>
            </div>
            <div class="flex items-center">
                <span class="w-32 font-medium text-gray-600">Gender:</span>
                <span class="text-gray-800">{{ $secretary->gender ?? '-' }}</span>
            </div>
            @php
    use Carbon\Carbon;
    $birthday = $secretary->birthday ? Carbon::parse($secretary->birthday)->format('F d, Y') : '-';
@endphp

<div class="flex items-center">
    <span class="w-32 font-medium text-gray-600">Birthday:</span>
    <span class="text-gray-800">{{ $birthday }}</span>
</div>

            <div class="flex items-center">
                <span class="w-32 font-medium text-gray-600">Status:</span>
                <span class="px-2 py-1 rounded-full text-sm font-semibold {{ $secretary->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ ucfirst($secretary->status ?? '-') }}
                </span>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-6 flex justify-end">
            <button @click="viewSecretaryModal = false" class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Close</button>
        </div>
    </div>
</div>


    <!-- ADD SECRETARY MODAL -->
    <div x-show="openSecretaryModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" x-cloak>
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
            <h2 class="text-xl font-semibold mb-4">Add Barangay Secretary</h2>
            <form action="{{ route('captain.secretary.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block font-semibold mb-1">First Name</label>
                    <input type="text" name="first_name" class="w-full border px-3 py-2 rounded" required>
                </div>
                <div class="mb-4">
                    <label class="block font-semibold mb-1">Middle Name</label>
                    <input type="text" name="middle_name" class="w-full border px-3 py-2 rounded">
                </div>
                <div class="mb-4">
                    <label class="block font-semibold mb-1">Last Name</label>
                    <input type="text" name="last_name" class="w-full border px-3 py-2 rounded" required>
                </div>
                <div class="mb-4">
                    <label class="block font-semibold mb-1">Email</label>
                    <input type="email" name="email" class="w-full border px-3 py-2 rounded" required>
                </div>
                <div class="mb-4">
                    <label class="block font-semibold mb-1">Gender</label>
                    <select name="gender" class="w-full border px-3 py-2 rounded" required>
                        <option value="">Select…</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block font-semibold mb-1">Birthday</label>
                    <input type="date" name="birthday" class="w-full border px-3 py-2 rounded" required>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" @click="openSecretaryModal = false" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Add Secretary</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function confirmToggle(secretaryId, status, fullName) {
    let action = status === 'active' ? 'deactivate' : 'activate';

    Swal.fire({
        title: `Are you sure?`,
        html: `You are about to <strong>${action}</strong> <strong>${fullName}</strong>.`,
        icon: status === 'active' ? 'warning' : 'success', // optional: differentiate colors
        showCancelButton: true,
        confirmButtonColor: status === 'active' ? '#dc2626' : '#16a34a',
        cancelButtonColor: '#6b7280',
        confirmButtonText: `Yes, ${action}!`,
        allowOutsideClick: false // user cannot close by clicking outside
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('toggle-form-' + secretaryId).submit();
        }
        // Do nothing if Cancel is clicked — SweetAlert will just close
    });
}

</script>

