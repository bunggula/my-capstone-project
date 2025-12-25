

<?php $__env->startSection('title', 'List of Residents'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex h-screen bg-gray-100 overflow-hidden">
    <?php echo $__env->make('partials.secretary-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1 flex flex-col overflow-hidden">
        <?php echo $__env->make('partials.secretary-header', ['title' => 'Residents'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="flex-1 overflow-y-auto p-6">
           

                <!-- Top Bar: Title Left, Button Right -->
<div class="flex flex-wrap justify-between items-center mb-4">
    <h2 class="text-2xl font-bold text-blue-900 mb-6">
        Residents of Barangay - <?php echo e(Auth::user()->barangay->name ?? 'N/A'); ?>

        <?php if(session('success')): ?>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: '<?php echo e(session('success')); ?>',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            });
        </script>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: '<?php echo e(session('error')); ?>',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            });
        </script>
    <?php endif; ?>
    </h2>
  
</div>

<div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
    
    <a href="<?php echo e(route('secretary.residents.index', array_merge(request()->query(), ['status' => 'approved', 'gender' => null]))); ?>"
       class="block p-4 rounded shadow border-l-4 <?php echo e(request('status') === 'approved' && !request('gender') ? 'bg-blue-200 border-blue-600 text-blue-900' : 'bg-blue-100 border-blue-500 text-blue-700'); ?>">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="font-semibold text-sm">Residents</h4>
                <p class="text-2xl font-bold"><?php echo e($counts['all'] ?? 0); ?></p>
            </div>
            <i data-lucide="users" class="w-8 h-8 opacity-60"></i>
        </div>
    </a>

    
    <a href="<?php echo e(route('secretary.residents.index', array_merge(request()->query(), ['status' => 'archived', 'gender' => null]))); ?>"
       class="block p-4 rounded shadow border-l-4 <?php echo e(request('status') === 'archived' ? 'bg-gray-200 border-gray-600 text-gray-900' : 'bg-gray-100 border-gray-400 text-gray-700'); ?>">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="font-semibold text-sm">Archived Residents</h4>
                <p class="text-2xl font-bold"><?php echo e($counts['archived'] ?? 0); ?></p>
            </div>
            <i data-lucide="archive" class="w-8 h-8 opacity-60"></i>
        </div>
    </a>

    
    <a href="<?php echo e(route('secretary.residents.index', array_merge(request()->query(), ['status' => 'approved', 'gender' => 'Male']))); ?>"
       class="block p-4 rounded shadow border-l-4 <?php echo e(request('gender') === 'Male' ? 'bg-green-200 border-green-600 text-green-900' : 'bg-green-100 border-green-500 text-green-700'); ?>">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="font-semibold text-sm">Male Residents</h4>
                <p class="text-2xl font-bold"><?php echo e($counts['male'] ?? 0); ?></p>
            </div>
            <i data-lucide="mars" class="w-10 h-10 opacity-70 text-green-600"></i>
        </div>
    </a>

    
    <a href="<?php echo e(route('secretary.residents.index', array_merge(request()->query(), ['status' => 'approved', 'gender' => 'Female']))); ?>"
       class="block p-4 rounded shadow border-l-4 <?php echo e(request('gender') === 'Female' ? 'bg-pink-200 border-pink-600 text-pink-900' : 'bg-pink-100 border-pink-500 text-pink-700'); ?>">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="font-semibold text-sm">Female Residents</h4>
                <p class="text-2xl font-bold"><?php echo e($counts['female'] ?? 0); ?></p>
            </div>
            <i data-lucide="venus" class="w-10 h-10 opacity-70 text-pink-600"></i>
        </div>
    </a>
</div>

<script>
    lucide.createIcons();
</script>




<form method="GET" action="<?php echo e(route('secretary.residents.index')); ?>" class="flex flex-wrap items-center justify-between gap-4 mb-6" id="residentSearchForm">

    <!-- Left side: search and combined filter -->
    <div class="flex flex-wrap gap-4">
        <!-- Search input -->
        <div class="relative w-full sm:w-64">
            <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                placeholder="Search by name or email..."
                class="border rounded px-4 py-2 w-full pr-10"
             oninput="clearTimeout(this.delay); this.delay = setTimeout(() => this.form.submit(), 100000)">

            <?php if(request('search')): ?>
                <button type="button"
                    onclick="document.querySelector('input[name=search]').value=''; document.getElementById('residentSearchForm').submit();"
                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-red-500 text-sm">
                    &times;
                </button>
            <?php endif; ?>
        </div>

       
<div class="flex items-center gap-2 mb-2">
  
    <select id="categoryFilter" name="category"
            class="border-gray-300 rounded px-3 py-2 shadow-sm text-gray-800"
            onchange="applyFilters()">
        <option value="">Select Category</option>
        <option value="PWD" <?php echo e(request('category') == 'PWD' ? 'selected' : ''); ?>>PWD</option>
        <option value="Senior" <?php echo e(request('category') == 'Senior' ? 'selected' : ''); ?>>Senior Citizen</option>
        <option value="Indigenous" <?php echo e(request('category') == 'Indigenous' ? 'selected' : ''); ?>>Indigenous People</option>
        <option value="Single Parent" <?php echo e(request('category') == 'Single Parent' ? 'selected' : ''); ?>>Single Parent</option>
    </select>
</div>


<div class="flex items-center gap-2 mb-2">
    
    <select id="voterFilter" name="voter"
            class="border-gray-300 rounded px-3 py-2 shadow-sm text-gray-800"
            onchange="applyFilters()">
        <option value="">Select Voter</option>
        <option value="Yes" <?php echo e(request('voter') == 'Yes' ? 'selected' : ''); ?>>Registered Voters</option>
        <option value="No" <?php echo e(request('voter') == 'No' ? 'selected' : ''); ?>>Non-Registered Voters</option>
    </select>
</div>
<div class="flex items-center gap-2 mb-2" id="clearFiltersWrapper" style="display: none;">
    <button type="button" 
            onclick="clearFilters()" 
            class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
        Clear
    </button>
</div>



<script>
    function updateClearButton() {
    const search = document.querySelector('input[name="search"]').value.trim();
    const category = document.getElementById('categoryFilter').value;
    const voter = document.getElementById('voterFilter').value;

    const wrapper = document.getElementById('clearFiltersWrapper');
    if (search || category || voter) {
        wrapper.style.display = 'flex';
    } else {
        wrapper.style.display = 'none';
    }
}

// Call this on page load
updateClearButton();

// Also call this whenever a filter changes
document.getElementById('categoryFilter').addEventListener('change', () => {
    updateClearButton();
    applyFilters();
});

document.getElementById('voterFilter').addEventListener('change', () => {
    updateClearButton();
    applyFilters();
});

document.querySelector('input[name="search"]').addEventListener('input', () => {
    // Wait a bit before showing button
    setTimeout(updateClearButton, 100);
});

// Clear filters function
function clearFilters() {
    document.querySelector('input[name="search"]').value = '';
    document.getElementById('categoryFilter').value = '';
    document.getElementById('voterFilter').value = '';
    document.getElementById('residentSearchForm').submit();
}

    function applyFilters() {
        const category = document.getElementById('categoryFilter').value;
        const voter = document.getElementById('voterFilter').value;

        const params = new URLSearchParams(window.location.search);
        category ? params.set('category', category) : params.delete('category');
        voter ? params.set('voter', voter) : params.delete('voter');

        window.location.search = params.toString();
    }
</script>



       <!-- ✅ Print Button (always visible) -->

    </div>


<script>
document.getElementById('combinedFilter').addEventListener('change', function() {
    const form = document.getElementById('residentSearchForm');
    const value = this.value;

    // Remove existing hidden category/voter inputs
    form.querySelectorAll('input[name="category"], input[name="voter"]').forEach(el => el.remove());

    if(value.includes('category:')) {
        const category = value.split(':')[1];
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'category';
        input.value = category;
        form.appendChild(input);
    } else if(value.includes('voter:')) {
        const voter = value.split(':')[1];
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'voter';
        input.value = voter;
        form.appendChild(input);
    }

    form.submit();
});

</script>


    <!-- Right side: Add Resident button -->
    <div class="flex items-center gap-2">
    <a href="<?php echo e(route('secretary.residents.print', [
        'category' => request('category'),
        'voter' => request('voter'),
        'search' => request('search')
    ])); ?>"
       target="_blank"
       class="px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-2 transition">
        <!-- Printer Icon (SVG) -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5h20v5a2 2 0 01-2 2h-2M6 14h12v8H6v-8z"/>
        </svg>
        Print
    </a>

    <button type="button" onclick="openModal()"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center gap-2">
        + Add Resident
    </button>
</div>

</form>



                <?php if($residents->count()): ?>
                <div class="bg-white shadow rounded-lg p-2">
                <div class="overflow-x-auto">
                    <table class="w-full table-auto border text-sm">
                        <thead class="bg-blue-600 text-white">
                            <tr>
                                <th class="px-4 py-2  text-left">#</th>
                                <th class="px-4 py-2  text-left">Full Name</th>
                                <th class="px-4 py-2  text-left">Gender</th>
                                <th class="px-4 py-2  text-left">Birthdate</th>
                                <th class="px-4 py-2 text-left">Civil Status</th>
                                <th class="px-4 py-2  text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $residents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="<?php echo e($loop->even ? 'bg-gray-100' : 'bg-white'); ?> hover:bg-gray-200">
                             <td class="px-4 py-2 "><?php echo e($residents->firstItem() + $loop->index); ?></td>
                             <td class="px-4 py-2 resident-name">
    <?php echo e($resident->first_name); ?> <?php echo e($resident->middle_name); ?> <?php echo e($resident->last_name); ?> <?php echo e($resident->suffix); ?>

</td>

                                <td class="px-4 py-2 "><?php echo e($resident->gender); ?></td>
                                <td class="px-4 py-2 "><?php echo e(\Carbon\Carbon::parse($resident->birthdate)->format('M d, Y')); ?></td>
                                <td class="px-4 py-2 "><?php echo e($resident->civil_status); ?></td>
                                <td class="px-4 py-2  text-center space-x-2">

                                    
                                    <button onclick='openViewModal(<?php echo json_encode($resident, 15, 512) ?>)'
            class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-md transition"
            title="View">
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

    
    <button onclick='openEditModal(<?php echo json_encode($resident, 15, 512) ?>)'
    class="bg-yellow-100 hover:bg-yellow-200 text-yellow-600 p-2 rounded-lg"
            title="Edit">
        <svg xmlns="http://www.w3.org/2000/svg"
             fill="none" viewBox="0 0 24 24"
             stroke-width="1.5" stroke="currentColor"
             class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
        </svg>
    </button>
    <?php if($resident->type === 'walk-in'): ?>
    <button 
    type="button"
    class="generate-document-btn bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-xs"
    data-resident='<?php echo json_encode($resident, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP, 512) ?>'
    title="Generate Document">
    Generate Document
</button>

<?php endif; ?>


 
    
    <form method="POST" 
      action="<?php echo e($resident->status === 'archived' 
                   ? route('secretary.residents.unarchive', $resident->id) 
                   : route('secretary.residents.archive', $resident->id)); ?>" 
      class="inline archive-form">
    <?php echo csrf_field(); ?>
    <button type="button" 
            data-status="<?php echo e($resident->status); ?>"
            class="<?php echo e($resident->status === 'archived' 
                        ? 'bg-green-600 hover:bg-green-700' 
                        : 'bg-yellow-600 hover:bg-yellow-700'); ?> 
                   text-white p-2 rounded-md transition archive-button"
            title="<?php echo e($resident->status === 'archived' ? 'Unarchive' : 'Archive'); ?>">
        <svg xmlns="http://www.w3.org/2000/svg"
             fill="none" viewBox="0 0 24 24"
             stroke-width="1.5" stroke="currentColor"
             class="w-4 h-4">
            <?php if($resident->status === 'archived'): ?>
                
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 10l9 6 9-6M4.5 6h15a1.5 1.5 0 011.5 1.5v9A1.5 1.5 0 0119.5 18h-15A1.5 1.5 0 013 16.5v-9A1.5 1.5 0 014.5 6z" />
            <?php else: ?>
                
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M20.25 7.5v9.75a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V7.5m16.5 0L12 12.75M3.75 7.5L12 12.75" />
            <?php endif; ?>
        </svg>
    </button>
</form>





                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                    <div class="mt-4">
    <?php echo e($residents->links()); ?>

</div>

                </div>
                <?php else: ?>
                    <p class="text-center text-gray-600 mt-10">No residents found.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>
<!-- Document Modal -->
<div id="documentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-xl shadow-lg w-full max-w-lg">
        <h3 class="text-xl font-semibold mb-4">
            Generate Document for <span id="residentName"></span>
        </h3>

        <form id="documentRequestForm" method="POST" action="<?php echo e(route('secretary.documents.request')); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="resident_id" id="resident_id">

            
            <label class="block mb-2 font-medium">Document</label>
            <select name="document_id" id="document_id" class="border rounded px-3 py-2 w-full mb-4" required>
                <option value="">Select Document</option>
                <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($doc->id); ?>"><?php echo e($doc->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            
            <label class="block mb-2 font-medium">Purpose</label>
<select name="purpose_id" id="purpose_id" class="border rounded px-3 py-2 w-full mb-4" required>
    <option value="">Select Purpose</option>
    <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($doc->id); ?>"><?php echo e($doc->purpose); ?></option>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <option value="other">Other</option>
</select>

<!-- Hidden input for "Other Purpose" -->
<div id="otherPurposeWrapper" class="hidden mb-4">
    <label class="block mb-1 font-medium">Specify Purpose</label>
    <input type="text" name="other_purpose" id="otherPurpose" class="border rounded px-3 py-2 w-full">
</div>


            
            <div id="additionalFields"></div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Generate & Print
                </button>
            </div>
        </form>
    </div>
</div>


<?php echo $__env->make('secretary.residents.create', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<?php echo $__env->make('secretary.residents.edit', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<div id="viewResidentModal" 
     class="fixed inset-0 bg-black bg-opacity-40 backdrop-blur-sm flex items-center justify-center hidden z-50">

    <div class="bg-white w-full max-w-3xl p-6 rounded-2xl shadow-2xl relative border border-gray-200 transform transition-all">
        
        <!-- Close Button -->
        <button onclick="closeViewModal()" 
                class="absolute top-3 right-3 text-gray-500 hover:text-red-500 transition text-2xl font-bold leading-none"
                title="Close">
            &times;
        </button>

        <!-- Header -->
        <div class="mb-4 border-b pb-3 flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" 
                      d="M5.121 17.804A13.937 13.937 0 0112 15c2.485 0 4.797.72 6.879 1.955M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <h2 class="text-xl font-bold text-blue-800">Resident Information</h2>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">

            <!-- Full Name -->
            <div class="p-3 bg-gray-50 rounded-lg shadow-sm">
                <p class="text-gray-500 font-semibold">Full Name</p>
                <p id="viewFullName" class="font-bold text-gray-900"></p>
            </div>

            <!-- Gender -->
            <div class="p-3 bg-gray-50 rounded-lg shadow-sm">
                <p class="text-gray-500 font-semibold">Gender</p>
                <p id="viewGender" class="font-bold text-gray-900"></p>
            </div>

            <!-- Birthdate -->
            <div class="p-3 bg-gray-50 rounded-lg shadow-sm">
                <p class="text-gray-500 font-semibold">Birthdate</p>
                <p id="viewBirthdate" class="font-bold text-gray-900"></p>
            </div>

            <!-- Age -->
            <div class="p-3 bg-gray-50 rounded-lg shadow-sm">
                <p class="text-gray-500 font-semibold">Age</p>
                <p id="viewAge" class="font-bold text-gray-900"></p>
            </div>

            <!-- Civil Status -->
            <div class="p-3 bg-gray-50 rounded-lg shadow-sm">
                <p class="text-gray-500 font-semibold">Civil Status</p>
                <p id="viewCivilStatus" class="font-bold text-gray-900"></p>
            </div>

            <!-- Category -->
            <div class="p-3 bg-gray-50 rounded-lg shadow-sm">
                <p class="text-gray-500 font-semibold">Category</p>
                <p id="viewCategory" class="font-bold text-gray-900"></p>
            </div>

            <!-- Email -->
            <div class="p-3 bg-gray-50 rounded-lg shadow-sm">
                <p class="text-gray-500 font-semibold">Email</p>
                <p id="viewEmail" class="font-bold text-gray-900"></p>
            </div>

            <!-- Phone -->
            <div class="p-3 bg-gray-50 rounded-lg shadow-sm">
                <p class="text-gray-500 font-semibold">Phone</p>
                <p id="viewPhone" class="font-bold text-gray-900"></p>
            </div>

            <!-- Zone -->
            <div class="p-3 bg-gray-50 rounded-lg shadow-sm">
                <p class="text-gray-500 font-semibold">Zone</p>
                <p id="viewZone" class="font-bold text-gray-900"></p>
            </div>

            <!-- Voter Status -->
            <div class="p-3 bg-gray-50 rounded-lg shadow-sm">
                <p class="text-gray-500 font-semibold">Voter Status</p>
                <span id="viewVoter" 
                      class="inline-block px-3 py-1 rounded-full text-xs font-medium mt-1">
                </span>
            </div>

        </div>
    </div>
</div>



<script>
    function openModal() {
        document.getElementById('addResidentModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('addResidentModal').classList.add('hidden');
    }

    function openEditModal(resident) {
        const modal = document.getElementById('editResidentModal');
        modal.classList.remove('hidden');

        const form = document.getElementById('editResidentForm');
        form.action = `/secretary/residents/${resident.id}`;

        document.getElementById('editResidentId').value = resident.id;
        document.getElementById('editFirstName').value = resident.first_name || '';
        document.getElementById('editMiddleName').value = resident.middle_name || '';
        document.getElementById('editLastName').value = resident.last_name || '';
        document.getElementById('editSuffix').value = resident.suffix || '';
        document.getElementById('editGender').value = resident.gender || '';
        document.getElementById('editBirthdate').value = resident.birthdate || '';
        document.getElementById('editCivilStatus').value = resident.civil_status || '';
        document.getElementById('editEmail').value = resident.email || '';
        document.getElementById('editPhone').value = resident.phone || '';
        document.getElementById('editZone').value = resident.zone || '';

        // Clear all category checkboxes
        document.querySelectorAll('#editResidentForm input[name="categories[]"]').forEach(cb => {
            cb.checked = false;
            cb.disabled = false; // re-enable everything first
        });

        // Check existing categories from DB
        if (resident.category) {
            const categories = resident.category.split(',');
            categories.forEach(cat => {
                const checkbox = document.querySelector(`#editResidentForm input[name="categories[]"][value="${cat.trim()}"]`);
                if (checkbox) checkbox.checked = true;
            });
        }

        // Auto-check Senior if applicable
        autoCheckSenior();
    }

    function closeEditModal() {
        document.getElementById('editResidentModal').classList.add('hidden');
    }

  function autoCheckSenior() {
    const birthdateInput = document.getElementById('editBirthdate');
    const seniorCheckbox = document.querySelector('#editResidentForm input[value="Senior"]');

    if (!birthdateInput || !seniorCheckbox) return;

    const birthdate = new Date(birthdateInput.value);
    const today = new Date();

    // Kung invalid date, enable checkbox (no restriction)
    if (isNaN(birthdate.getTime())) {
        seniorCheckbox.checked = false;
        seniorCheckbox.disabled = false;
        return;
    }

    // Compute age
    let age = today.getFullYear() - birthdate.getFullYear();
    const m = today.getMonth() - birthdate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthdate.getDate())) {
        age--;
    }

    // ✅ Apply rule
    if (age >= 60) {
        seniorCheckbox.checked = true;
        seniorCheckbox.disabled = true; // lock it
    } else {
        seniorCheckbox.checked = false; // auto uncheck if below 60
        seniorCheckbox.disabled = true; // prevent clicking
    }
}

    // Recalculate senior status when birthdate is changed
    document.getElementById('editBirthdate').addEventListener('input', autoCheckSenior);

    function openViewModal(resident) {
    const modal = document.getElementById('viewResidentModal');
    modal.classList.remove('hidden');

    // Full Name
    const fullName = [resident.first_name, resident.middle_name, resident.last_name, resident.suffix]
        .filter(Boolean)
        .join(' ');
    document.getElementById('viewFullName').innerText = fullName || 'N/A';

    // Gender
    document.getElementById('viewGender').innerText = resident.gender ?? 'N/A';

    // Birthdate
    document.getElementById('viewBirthdate').innerText = resident.birthdate ?? 'N/A';

    // Age calculation
    const birth = new Date(resident.birthdate);
    const today = new Date();
    let age = today.getFullYear() - birth.getFullYear();
    const m = today.getMonth() - birth.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) {
        age--;
    }
    document.getElementById('viewAge').innerText = isNaN(age) ? 'N/A' : age;

    // Other details
    document.getElementById('viewCivilStatus').innerText = resident.civil_status ?? 'N/A';
    document.getElementById('viewCategory').innerText = resident.category ?? 'N/A';
    document.getElementById('viewEmail').innerText = resident.email ?? 'N/A';
    document.getElementById('viewPhone').innerText = resident.phone ?? 'N/A';
    document.getElementById('viewZone').innerText = resident.zone ?? 'N/A';

    // Voter Status Badge
    const voterEl = document.getElementById('viewVoter');
voterEl.textContent = resident.voter ?? 'N/A';
voterEl.className = "px-3 py-1 rounded-full text-sm font-medium"; // reset classes

if (resident.voter && resident.voter.toLowerCase() === "yes") {
    voterEl.textContent = "Registered";
    voterEl.style.color = "green"; // text color lang
    voterEl.style.backgroundColor = "transparent"; // optional: remove background
} else if (resident.voter && resident.voter.toLowerCase() === "no") {
    voterEl.textContent = "Not Registered";
    voterEl.style.color = "red"; // text color lang
    voterEl.style.backgroundColor = "transparent";
} else {
    voterEl.textContent = "N/A";
    voterEl.style.color = "gray";
    voterEl.style.backgroundColor = "transparent";
}
    }
function closeViewModal() {
    document.getElementById('viewResidentModal').classList.add('hidden');
}

document.querySelectorAll('.archive-button').forEach(button => {
    button.addEventListener('click', function () {
        const form = this.closest('form');
        const status = this.dataset.status;

        const isArchived = status === 'archived';
        const action = isArchived ? 'Unarchive' : 'Archive';

        // Get the resident's full name from the row
        // Assuming the first td contains the resident's name
        const row = this.closest('tr');
        const nameCell = row.querySelector('.resident-name');
        const residentName = nameCell ? nameCell.innerText.trim() : 'Resident';

        Swal.fire({
            title: `${action} ${residentName}?`,
            text: isArchived
                ? `${residentName} will be restored to the approved list.`
                : `${residentName} will be moved to archive.`,
            icon: isArchived ? 'info' : 'warning',
            showCancelButton: true,
            confirmButtonColor: isArchived ? '#16a34a' : '#f59e0b', // green or amber
            cancelButtonColor: '#d33',
            confirmButtonText: `Yes, ${action.toLowerCase()} it!`
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
 // Open modal and populate resident info
function openDocumentModal(resident) {
    const modal = document.getElementById('documentModal');
    modal.classList.remove('hidden');

    // Full name including middle name and suffix
    const fullName = [resident.first_name, resident.middle_name, resident.last_name, resident.suffix]
        .filter(Boolean)
        .join(' ');
    document.getElementById('residentName').innerText = fullName;

    // Set hidden resident_id input
    document.getElementById('resident_id').value = resident.id;

    // Reset document dropdown
    document.getElementById('document_id').value = '';

    // Reset purpose dropdown
    resetPurposeDropdown();
    hideOtherPurpose();
    document.getElementById('additionalFields').innerHTML = '';
}

// Reset Purpose dropdown
function resetPurposeDropdown() {
    const purposeSelect = document.getElementById('purpose_id');
    purposeSelect.innerHTML = '<option value="">Select Purpose</option>';
}

// Show/Hide "Other Purpose" input
function hideOtherPurpose() {
    const otherWrapper = document.getElementById('otherPurposeWrapper');
    otherWrapper.classList.add('hidden');
    document.getElementById('otherPurpose').required = false;
}

// When document changes, fetch purposes and additional fields
document.getElementById('document_id').addEventListener('change', async function() {
    const documentId = this.value;
    const purposeSelect = document.getElementById('purpose_id');
    const additionalFieldsContainer = document.getElementById('additionalFields');
    additionalFieldsContainer.innerHTML = ''; // clear previous fields

    // Reset purpose dropdown
    resetPurposeDropdown();
    hideOtherPurpose();

    if (!documentId) return;

    purposeSelect.disabled = true;
    purposeSelect.innerHTML = '<option value="">Loading purposes...</option>';

    try {
        // Fetch purposes
        const resPurposes = await fetch(`/secretary/documents/${documentId}/purposes`);
        const purposes = await resPurposes.json();

        purposeSelect.disabled = false;
        resetPurposeDropdown();

        if (purposes.length === 0) {
            purposeSelect.innerHTML = '<option value="">No purposes available</option>';
        } else {
            purposes.forEach(purpose => {
                const option = document.createElement('option');
                option.value = purpose.id;
                const price = parseFloat(purpose.price).toFixed(2);
                option.textContent = `${purpose.purpose} (${price})`;
                purposeSelect.appendChild(option);
            });
        }

        // ✅ Add "Other" option at the end
        const otherOption = document.createElement('option');
        otherOption.value = 'other';
        otherOption.textContent = 'Other';
        purposeSelect.appendChild(otherOption);

    } catch (err) {
        purposeSelect.disabled = false;
        resetPurposeDropdown();
        console.error(err);
        Swal.fire('Error', 'Failed to load purposes. Try again.', 'error');
    }

    // Fetch additional fields
    try {
        const resFields = await fetch(`/secretary/documents/${documentId}/fields`);
        const dataFields = await resFields.json();

        dataFields.fields.forEach(field => {
            const wrapper = document.createElement('div');
            wrapper.classList.add('mb-4');

            const label = document.createElement('label');
            label.textContent = field.label;
            label.classList.add('block', 'mb-1', 'font-medium');

            const input = document.createElement('input');
            input.type = field.type || 'text';
            input.name = `form_data[${field.key}]`;
            input.classList.add('border', 'rounded', 'px-3', 'py-2', 'w-full');

            if (field.max) input.max = field.max;

            wrapper.appendChild(label);
            wrapper.appendChild(input);
            additionalFieldsContainer.appendChild(wrapper);
        });
    } catch (err) {
        console.error(err);
        Swal.fire('Error', 'Failed to load additional fields. Try again.', 'error');
    }
});

// Show/Hide "Other Purpose" input when purpose changes
document.getElementById('purpose_id').addEventListener('change', function() {
    const otherWrapper = document.getElementById('otherPurposeWrapper');
    if (this.value === 'other') {
        otherWrapper.classList.remove('hidden');
        document.getElementById('otherPurpose').required = true;
    } else {
        hideOtherPurpose();
    }
});

// Attach click event to generate buttons
document.querySelectorAll('.generate-document-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const resident = JSON.parse(this.dataset.resident);
        openDocumentModal(resident);
    });
});


</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/residents/index.blade.php ENDPATH**/ ?>