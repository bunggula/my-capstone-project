@extends('layouts.app')

@section('title', 'All Barangays')

@section('content')
<div class="flex h-screen w-screen overflow-hidden font-sans">

    <!-- Sidebar -->
    @include('partials.abc-sidebar')

    <!-- Main Area -->
    <main class="flex-1 relative overflow-hidden">

        <!-- Background -->
        <div class="absolute inset-0 bg-cover bg-center z-0" style="background-image: url('/images/back.jpg');"></div>
        <div class="absolute inset-0 bg-white bg-opacity-80 z-10"></div>

     <!-- Content -->
<div class="relative z-20 overflow-y-auto p-8" style="height: calc(100vh - 64px);">

<div class="max-w-7xl mx-auto flex justify-between items-center mb-4">
    <h2 class="text-3xl font-bold text-black-900">All Barangays</h2>
    <button id="addBarangayBtn" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-green-700">Add Barangay</button>
</div>

<div id="alertContainer" class="mb-4"></div>

<div class="bg-white rounded-xl p-6 shadow-md">
    <h2 class="text-xl font-semibold mb-4">List of Barangays ({{ $barangays->count() }})</h2>

    <!-- Municipality Section -->
    <div class="mb-6 flex items-center gap-4">
        <h2 class="text-xl font-semibold flex items-center gap-2">
            Municipality:
            <span id="municipalityName">{{ optional($barangays->first()->municipality)->name ?? 'N/A' }}</span>
        </h2>

        <div class="flex items-center gap-2">
            <img id="municipalityMainLogo" 
                 src="{{ optional($barangays->first()->municipality)->logo 
                         ? asset('storage/' . $barangays->first()->municipality->logo) 
                         : asset('images/default_logo.png') }}" 
                 alt="Logo" 
                 class="w-10 h-10 object-cover rounded">

            @if($barangays->first()->municipality)
                <button id="editMunicipalityBtn" 
                    data-id="{{ $barangays->first()->municipality->id }}" 
                    data-url="/abc/municipality/{{ $barangays->first()->municipality->id }}/update"
                    class="bg-yellow-100 hover:bg-yellow-200 text-yellow-600 p-2 rounded-md transition">
                    <!-- Pencil Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-3 h-3">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
                    </svg>
                </button>
            @endif
        </div>
    </div>


                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="barangay-grid">
                    @foreach($barangays as $barangay)
                        <div class="bg-white shadow-md rounded-xl p-4 hover:bg-blue-50 transition" 
                            data-id="{{ $barangay->id }}" 
                            data-name="{{ $barangay->name }}">
                            
                            <h3 class="text-lg font-semibold text-gray-800">{{ $barangay->name }}</h3>
                            <p class="text-sm text-green-600 font-medium mt-1">Residents: {{ $barangay->approved_residents_count }}</p>
                            
                            <div class="mt-2 flex gap-2">
                                <button class="editBarangayBtn bg-yellow-100 hover:bg-yellow-200 text-yellow-600 p-2 rounded-md transition" 
                                        data-id="{{ $barangay->id }}" 
                                        data-name="{{ $barangay->name }}">
                                    <!-- Pencil SVG -->
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                </button>

                                <button class="deleteBarangayBtn bg-red-600 hover:bg-red-700 text-white p-2 rounded-md transition" 
                                        data-id="{{ $barangay->id }}" 
                                        data-name="{{ $barangay->name }}">
                                    <!-- Trash SVG -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0a1 1 0 011 1v0a1 1 0 01-1 1H7a1 1 0 01-1-1v0a1 1 0 011-1h10z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Barangay Modal -->
<div id="modalOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg w-96">
        <div class="px-6 py-4 border-b">
            <h3 id="modalTitle" class="text-lg font-semibold"></h3>
        </div>
        <form id="modalForm" class="px-6 py-4">
            @csrf
            <input type="hidden" id="modalBarangayId">
            <div class="mb-4">
                <label class="block mb-1">Barangay Name</label>
                <input type="text" id="modalBarangayName" name="name" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" id="modalCancel" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">Cancel</button>
                <button type="submit" id="modalSubmit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Municipality Modal -->
<div id="municipalityModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg w-96">
        <div class="px-6 py-4 border-b">
            <h3 class="text-lg font-semibold">Edit Municipality</h3>
        </div>
        <form id="municipalityForm" class="px-6 py-4" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block mb-1">Municipality Name</label>
                <input type="text" id="municipalityInput" class="w-full border rounded px-3 py-2" required
                    value="{{ $barangays->first()->municipality->name ?? '' }}">
            </div>
            <div class="mb-4">
                <label class="block mb-1">Municipality Logo</label>
                <img id="municipalityLogoPreview" 
                     src="{{ $barangays->first()->municipality->logo ? asset('storage/' . $barangays->first()->municipality->logo) : '' }}" 
                     class="w-20 h-20 mb-2 object-cover rounded {{ $barangays->first()->municipality->logo ? '' : 'hidden' }}">
                <input type="file" id="municipalityLogo" name="logo" class="w-full border rounded px-3 py-2" accept="image/*">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" id="municipalityCancel" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">Cancel</button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</div>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {

    // =======================
    // Barangay Modal
    // =======================
    const addBtn = document.getElementById('addBarangayBtn');
    const modalOverlay = document.getElementById('modalOverlay');
    const modalTitle = document.getElementById('modalTitle');
    const modalForm = document.getElementById('modalForm');
    const modalBarangayId = document.getElementById('modalBarangayId');
    const modalBarangayName = document.getElementById('modalBarangayName');
    const modalCancel = document.getElementById('modalCancel');
    let isEdit = false;

    const showToast = (msg, icon='success') => {
        Swal.fire({ toast:true, position:'top-end', icon, title: msg, showConfirmButton:false, timer:2000, timerProgressBar:true });
    }

    addBtn.addEventListener('click', () => {
        isEdit = false;
        modalTitle.textContent = 'Add Barangay';
        modalBarangayId.value = '';
        modalBarangayName.value = '';
        modalOverlay.classList.remove('hidden');
    });

    modalCancel.addEventListener('click', () => modalOverlay.classList.add('hidden'));

    document.querySelectorAll('.editBarangayBtn').forEach(btn => {
        btn.addEventListener('click', () => {
            isEdit = true;
            modalTitle.textContent = 'Edit Barangay';
            modalBarangayId.value = btn.dataset.id;
            modalBarangayName.value = btn.dataset.name;
            modalOverlay.classList.remove('hidden');
        });
    });

    modalForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const id = modalBarangayId.value;
        const name = modalBarangayName.value;
        const url = isEdit ? `/abc/barangays/${id}` : `{{ route('abc.barangays.store') }}`;
        const token = document.querySelector('input[name="_token"]').value;

        const formData = new FormData();
        formData.append('name', name);
        formData.append('_token', token);
        if(isEdit) formData.append('_method','PUT');

        try {
            const res = await fetch(url, { method:'POST', body:formData });
            const data = await res.json();
            if(res.ok && data.success){
                modalOverlay.classList.add('hidden');
                showToast(`Barangay ${isEdit?'updated':'added'} successfully!`);
                setTimeout(()=>location.reload(),1000);
            } else throw new Error(data.message || 'Failed');
        } catch(err) {
            showToast(`Failed to ${isEdit?'update':'add'} barangay`, 'error');
        }
    });

    document.querySelectorAll('.deleteBarangayBtn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const name = btn.dataset.name;
            Swal.fire({
                title: 'Are you sure?',
                text: `Delete barangay: ${name}?`,
                icon:'warning',
                showCancelButton:true,
                confirmButtonColor:'#d33',
                cancelButtonColor:'#3085d6',
                confirmButtonText:'Yes, delete it!'
            }).then(async result=>{
                if(result.isConfirmed){
                    const token = document.querySelector('input[name="_token"]').value;
                    const formData = new FormData();
                    formData.append('_token', token);
                    formData.append('_method','DELETE');
                    const res = await fetch(`/abc/barangays/${id}`, { method:'POST', body: formData });
                    const data = await res.json();
                    if(res.ok && data.success){
                        showToast('Barangay deleted.');
                        setTimeout(()=>location.reload(),1000);
                    } else showToast('Failed to delete barangay','error');
                }
            });
        });
    });

    // =======================
    // Municipality Modal
    // =======================
    const editBtn = document.getElementById('editMunicipalityBtn');
    if(editBtn){
        const modal = document.getElementById('municipalityModal');
        const cancelBtn = document.getElementById('municipalityCancel');
        const form = document.getElementById('municipalityForm');
        const input = document.getElementById('municipalityInput');
        const nameSpan = document.getElementById('municipalityName');
        const logoInput = document.getElementById('municipalityLogo');
        const logoPreview = document.getElementById('municipalityLogoPreview');
        const mainLogo = document.getElementById('municipalityMainLogo');
        const url = editBtn.dataset.url;

        editBtn.addEventListener('click', ()=>modal.classList.remove('hidden'));
        cancelBtn.addEventListener('click', ()=>modal.classList.add('hidden'));

        // Preview selected logo instantly
        logoInput.addEventListener('change', (e)=>{
            const file = e.target.files[0];
            if(file){
                const reader = new FileReader();
                reader.onload = ev=>{
                    logoPreview.src = ev.target.result;
                    logoPreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        // Submit municipality update
        form.addEventListener('submit', async e=>{
            e.preventDefault();
            const name = input.value.trim();
            if(!name) return Swal.fire({ icon:'error', title:'Name cannot be empty', toast:true, position:'top-end', timer:1500, showConfirmButton:false });

            const token = document.querySelector('input[name="_token"]').value;
            const logoFile = logoInput.files[0];
            const formData = new FormData();
            formData.append('name', name);
            if(logoFile) formData.append('logo', logoFile);
            formData.append('_token', token);

            try{
                const res = await fetch(url, { method:'POST', body:formData });
                const data = await res.json();
                if(res.ok && data.success){
                    nameSpan.textContent = name;
                    if(data.logo_url){
                        logoPreview.src = data.logo_url;
                        logoPreview.classList.remove('hidden');
                        mainLogo.src = data.logo_url; // update main logo too
                        mainLogo.classList.remove('hidden');
                    }
                    modal.classList.add('hidden');
                    Swal.fire({ icon:'success', title:'Municipality updated!', toast:true, position:'top-end', timer:1500, showConfirmButton:false });
                } else Swal.fire({ icon:'error', title: data.message || 'Failed', toast:true, position:'top-end', showConfirmButton:false });
            }catch(err){
                console.error(err);
                Swal.fire({ icon:'error', title:'Failed to update municipality', toast:true, position:'top-end', showConfirmButton:false });
            }
        });
    }

});
</script>

@endsection
