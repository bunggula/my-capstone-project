

<?php $__env->startSection('title', 'Blotter Logbook'); ?>
<style>
/* Limit modal height at gawin scrollable */
#addBlotterModal > div {
    max-height: 90vh;      /* maximum 90% ng screen height */
    overflow-y: auto;      /* scroll kapag lumampas sa max-height */
    box-sizing: border-box; /* siguraduhing kasama ang padding sa height */
}

/* Optional: scrollbar style */
#addBlotterModal > div::-webkit-scrollbar {
    width: 8px;
}

#addBlotterModal > div::-webkit-scrollbar-thumb {
    background-color: rgba(0,0,0,0.3);
    border-radius: 4px;
}
/* Limit modal height at gawin scrollable */
#addBlotterModal > div,
#editBlotterModal > div {
    max-height: 90vh;      /* maximum 90% ng screen height */
    overflow-y: auto;      /* scroll kapag lumampas sa max-height */
    box-sizing: border-box; /* kasama ang padding sa height */
    position: relative;     /* para sa absolute close button */
}

/* Optional: scrollbar style */
#addBlotterModal > div::-webkit-scrollbar,
#editBlotterModal > div::-webkit-scrollbar {
    width: 8px;
}

#addBlotterModal > div::-webkit-scrollbar-thumb,
#editBlotterModal > div::-webkit-scrollbar-thumb {
    background-color: rgba(0,0,0,0.3);
    border-radius: 4px;
}

</style>
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">

<?php $__env->startSection('content'); ?>
<div class="flex h-screen bg-gray-100 overflow-hidden">
    <?php echo $__env->make('partials.secretary-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1 flex flex-col overflow-hidden">
        <?php echo $__env->make('partials.secretary-header', ['title' => 'Residents'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="flex-1 overflow-y-auto p-6">

            <div class="mb-4">
    
    <h1 class="text-2xl font-bold text-blue-900 mb-6">Barangay Blotter Logbook</h1>

    
    <div class="flex justify-between items-start mb-4">
        
        <div class="block p-4 rounded shadow border-l-4 bg-gray-100 border-gray-400 text-gray-700 w-full sm:w-1/3">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="font-semibold text-sm">Total Blotters</h4>
                    <p class="text-2xl font-bold"><?php echo e($totalBlotters); ?></p>
                </div>
                <svg class="h-8 w-8 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2M3 20h18M9 10h6M9 14h6"/>
                </svg>
            </div>
        </div>

        
        <div class="ml-4 mt-4 sm:mt-0">
           
        </div>
    </div>

 
<div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:space-x-4 space-y-2 sm:space-y-0 w-full">

    <form id="filterForm" action="<?php echo e(route('secretary.blotter.index')); ?>" method="GET"
          class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 space-y-2 sm:space-y-0 w-full">

        
        <input type="text" name="search" id="searchInput" value="<?php echo e(request('search')); ?>" placeholder="Search blotter..."
               class="border border-gray-300 rounded-md px-4 py-2 w-full sm:w-1/3 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

        
        <select name="month" id="monthSelect"
                class="border border-gray-300 rounded-md px-4 py-2 w-full sm:w-1/5 text-sm bg-white text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            <option value="">Select Months</option>
            <?php $__currentLoopData = range(1,12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($m); ?>" <?php echo e(request('month') == $m ? 'selected' : ''); ?>>
                    <?php echo e(DateTime::createFromFormat('!m', $m)->format('F')); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        
        <select name="year" id="yearSelect"
                class="border border-gray-300 rounded-md px-4 py-2 w-full sm:w-1/6 text-sm bg-white text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            <option value="">Select Years</option>
            <?php $__currentLoopData = range(date('Y'), date('Y')-10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($y); ?>" <?php echo e(request('year') == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        
        <?php if(request('search') || request('month') || request('year')): ?>
            <a href="<?php echo e(route('secretary.blotter.index')); ?>"
               class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm shadow-sm transition">
                Clear
            </a>
        <?php endif; ?>

        
        <div class="flex justify-end mt-2 sm:mt-0 w-full">
            <button type="button" onclick="openAddModal()"
                    class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800 transition">
                + Add Record
            </button>
        </div>

    </form>
</div>

</div>



<script>
    document.getElementById('monthSelect').addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });

    document.getElementById('yearSelect').addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });

    // Optional: trigger search when pressing Enter in search input
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('filterForm').submit();
        }
    });
</script>


            
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

            
            <div class="bg-white shadow rounded-lg p-4">
                        <div class="overflow-x-auto">
                    <table id="blotterTable" class="w-full table-auto border text-sm text-left">
                        <thead class="bg-blue-600 text-white">
                            <tr>
                                <th class="px-4 py-2 text-left">#</th>
                                <th class="px-4 py-2 text-left">Date</th>
                                <th class="px-4 py-2 text-left">Time</th>
                                <th class="px-4 py-2 text-left">Complainants</th>
                                <th class="px-4 py-2 text-left">Respondents</th>
                                <th class="px-4 py-2 text-center"></th>

                                <th class="px-4 py-2 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $blotters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="<?php echo e($loop->even ? 'bg-gray-100' : 'bg-white'); ?> hover:bg-gray-200">
                                    
                                     <td class="px-4 py-2">
                                        <?php echo e($blotters->firstItem() + $loop->index); ?>

                                    </td>
                                    <td class="px-4 py-2">
                                        <?php echo e(\Carbon\Carbon::parse($b->date)->format('F j, Y')); ?>

                                    </td>

                                    
                                    <td class="px-4 py-2">
                                        <?php echo e(\Carbon\Carbon::parse($b->time)->format('g:i a')); ?>

                                    </td>

                                    
                                    <td class="px-4 py-2">
                                        <ul class="ml-2 space-y-1 list-none">
                                            <?php $__currentLoopData = $b->complainants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li><?php echo e($c->first_name); ?> <?php echo e($c->middle_name ?? ''); ?> <?php echo e($c->last_name); ?></li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    </td>

                                    
                                    <td class="px-4 py-2">
                                        <ul class="ml-2 space-y-1 list-none">
                                            <?php $__currentLoopData = $b->respondents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li><?php echo e($r->first_name); ?> <?php echo e($r->middle_name ?? ''); ?> <?php echo e($r->last_name); ?></li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                        
                                    </td>
   
                                    
                                    <td class="px-4 py-2">
                                    <?php if($b->is_signed): ?>
    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-md text-xs font-semibold">Signed</span>
<?php endif; ?>

                                        
                                    </td>
   
                                    
                                    <td class="px-4 py-2 text-center space-x-2 flex justify-center">
                                        
                                        <button onclick="openViewModal(<?php echo e($b->id); ?>)"
                                                class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-md transition"
                                                title="View">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 
                                                    4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>

                                        
                                        <button onclick="openEditModal(<?php echo e($b->id); ?>)"
                                                class="bg-yellow-100 hover:bg-yellow-200 text-yellow-600 p-2 rounded-md transition"
                                                title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor"
                                                class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 
                                                    2.5a2.121 2.121 0 113 3L12 15l-4 
                                                    1 1-4 9.5-9.5z" />
                                            </svg>
                                        </button>

                                        
                                        <a href="<?php echo e(route('secretary.blotter.print', $b->id)); ?>" target="_blank"
                                            class="bg-green-600 hover:bg-green-700 text-white p-2 rounded-md transition"
                                            title="Print">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 9V2h12v7M6 18H4a2 2 0 
                                                    01-2-2v-5h20v5a2 2 0 01-2 
                                                    2h-2M6 14h12v8H6v-8z"/>
                                            </svg>
                                        </a>
                                        
<?php if(!$b->is_signed): ?>
    <button onclick="openUploadModal(<?php echo e($b->id); ?>)"
            class="bg-purple-600 hover:bg-purple-700 text-white p-2 rounded-md transition"
            title="Upload Signed Blotter">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 12v8m0 0l-4-4m4 4l4-4M12 4v4"/>
        </svg>
    </button>
<?php endif; ?>


                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>

                    <div class="mt-4">
                        <?php echo e($blotters->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
document.getElementById('searchInput').addEventListener('keyup', function () {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll('#blotterTable tbody tr');

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
});
</script>


<div id="viewBlotterModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center hidden z-50 p-4">
    <div class="bg-white w-full max-w-3xl rounded-2xl shadow-lg p-6 relative overflow-y-auto max-h-[90vh]">

        <!-- Close Button -->
        <button onclick="closeViewModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>

        <!-- Modal Title -->
        <h2 class="text-2xl font-semibold text-blue-900 mb-6">View Blotter Details</h2>

        <!-- Date & Time -->
        <p class="text-gray-700 mb-4">
            <strong class="font-medium">Date & Time:</strong> 
            <span id="viewDateTime" class="ml-2"></span>
        </p>

        <!-- Complainants -->
        <div class="mb-4">
            <strong class="font-medium text-gray-800">Complainants:</strong>
            <ul id="viewComplainants" class="ml-6 mt-2 space-y-2">
                <!-- Example item format: <li class="bg-gray-50 p-2 rounded border">John Doe</li> -->
            </ul>
        </div>

        <!-- Respondents -->
        <div class="mb-4">
            <strong class="font-medium text-gray-800">Respondents:</strong>
            <ul id="viewRespondents" class="ml-6 mt-2 space-y-2">
                <!-- Example item format: <li class="bg-gray-50 p-2 rounded border">Jane Smith</li> -->
            </ul>
        </div>

        <!-- Description -->
        <div class="mb-4">
            <strong class="font-medium text-gray-800">Description:</strong>
            <p id="viewDescription" class="bg-gray-50 p-3 rounded border border-gray-200 mt-1 text-gray-700"></p>
        </div>
        <!-- Signed File Preview -->
        <div class="mb-4" id="viewSignedContainer" style="display:none;">
    <strong class="font-medium text-gray-800">Signed File:</strong>
    <div id="viewSignedPreview" class="border p-2 rounded max-h-60 overflow-auto mt-2"></div>
</div>

</div>

    </div>
</div>
<div id="uploadBlotterModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center hidden z-50 p-4">
    <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6 relative overflow-y-auto max-h-[90vh]">
        <button onclick="closeUploadModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>

        <h2 class="text-xl font-bold text-blue-900 mb-4">Upload Signed Blotter</h2>

        <form id="uploadForm" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <input type="hidden" id="uploadBlotterId" name="blotter_id" value="">

            <div class="mb-4">
                <label class="block font-medium mb-1">Select File (PDF/JPG/PNG)</label>
                <input type="file" name="signed_file" accept=".pdf,.jpg,.png" required class="border p-2 rounded w-full">
            </div>
<!-- Preview container -->
<div class="mb-4" id="uploadPreviewContainer" style="display:none;">
    <label class="block font-medium mb-1">Preview:</label>
    <div id="uploadPreview" class="border p-2 rounded max-h-60 overflow-auto"></div>
</div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeUploadModal()" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Cancel</button>
                <button type="submit" class="bg-purple-700 text-white px-4 py-2 rounded hover:bg-purple-800">Upload</button>
            </div>
        </form>
    </div>
</div>


<div id="addBlotterModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center hidden z-50 p-4">
    <div class="bg-white w-full max-w-3xl rounded-lg shadow-lg p-6 relative overflow-y-auto max-h-[90vh]">

        
        <button onclick="closeAddModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl">&times;</button>

        <h2 class="text-xl font-bold text-blue-900 mb-4">Add Blotter Record</h2>

        <form action="<?php echo e(route('secretary.blotter.store')); ?>" method="POST" class="space-y-4">
            <?php echo csrf_field(); ?>

            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium">Date</label>
                    <input type="date" name="date" class="border p-2 w-full rounded" required max="<?php echo e(date('Y-m-d')); ?>">
                </div>
                <div>
                    <label class="block font-medium">Time</label>
                    <input type="time" name="time" class="border p-2 w-full rounded" required>
                </div>
            </div>

            
            <div class="border rounded p-4">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-semibold text-gray-700">Complainants</h3>
                    <button type="button" onclick="addComplainant()" class="text-sm bg-green-600 text-white px-2 py-1 rounded">+ Add</button>
                </div>
                <div id="addComplainantsContainer"></div>
            </div>

            
            <div class="border rounded p-4">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-semibold text-gray-700">Respondents</h3>
                    <button type="button" onclick="addRespondent()" class="text-sm bg-green-600 text-white px-2 py-1 rounded">+ Add</button>
                </div>
                <div id="addRespondentsContainer"></div>
            </div>

            
            <div>
                <label class="block font-medium">Description</label>
                <textarea name="description" rows="3" class="border p-2 w-full rounded" required></textarea>
            </div>

            
            <div class="flex flex-col sm:flex-row justify-end gap-2">
                <button type="button" onclick="closeAddModal()" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Cancel</button>
                <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800">Save</button>
            </div>
        </form>
    </div>
</div>

<div id="editBlotterModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center hidden z-50">
    <div class="bg-white w-full max-w-3xl rounded-lg shadow-lg p-6 relative overflow-y-auto max-h-[90vh]">

        <button onclick="closeEditModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl">&times;</button>
        <h2 class="text-xl font-bold text-blue-900 mb-4">Edit Blotter Record</h2>

        <form id="editForm" method="POST" enctype="multipart/form-data" class="space-y-4">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium">Date</label>
                    <input type="date" name="date" id="editDate" class="border p-2 w-full rounded" required>
                </div>
                <div>
                    <label class="block font-medium">Time</label>
                    <input type="time" name="time" id="editTime" class="border p-2 w-full rounded" required>
                </div>
            </div>

            
            <div class="border rounded p-4">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-semibold text-gray-700">Complainants</h3>
                    <button type="button" onclick="addComplainant('edit')" class="text-sm bg-green-600 text-white px-2 py-1 rounded">+ Add</button>
                </div>
                <div id="editComplainantsContainer"></div>
            </div>

            
            <div class="border rounded p-4">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-semibold text-gray-700">Respondents</h3>
                    <button type="button" onclick="addRespondent('edit')" class="text-sm bg-green-600 text-white px-2 py-1 rounded">+ Add</button>
                </div>
                <div id="editRespondentsContainer"></div>
            </div>

            
            <div>
                <label class="block font-medium">Description</label>
                <textarea name="description" id="editDescription" rows="3" class="border p-2 w-full rounded" required></textarea>
            </div>

            
            <div id="editSignedContainer" class="mb-4" style="display:none;">
                <label class="block font-medium mb-1">Current Signed File:</label>
                <div id="editSignedPreview" class="border p-2 rounded max-h-60 overflow-auto"></div>
            </div>

            
            <div class="mb-4">
                <label class="block font-medium mb-1">Replace Signed File (PDF/JPG/PNG)</label>
                <input type="file" name="signed_file" accept=".pdf,.jpg,.png" class="border p-2 rounded w-full" id="editSignedInput">
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeEditModal()" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Cancel</button>
                <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800">Update</button>
            </div>
        </form>
    </div>
</div>



<script>
    let residents = <?php echo json_encode($residents, 15, 512) ?>; // Laravel JSON of residents
    let addComplainantCount = 0, addRespondentCount = 0;
    let editComplainantCount = 0, editRespondentCount = 0;

    // Add Complainant row
    function addComplainant(type='add', data=null){
    const container = type==='edit'
        ? document.getElementById('editComplainantsContainer')
        : document.getElementById('addComplainantsContainer');

    const count = type==='edit' ? editComplainantCount : addComplainantCount;

    const div = document.createElement('div');
    div.classList.add('p-3','border','rounded','space-y-3','bg-gray-50','mb-2'); // ← IMPORTANT

    let options = '<option value="">Select Resident</option>';
    residents.forEach(r => {
        const selected = data && data.resident_id == r.id ? 'selected' : '';
        options += `<option value="${r.id}" ${selected}
            data-first="${r.first_name}" 
            data-middle="${r.middle_name}" 
            data-last="${r.last_name}">
            ${r.first_name} ${r.middle_name ?? ''} ${r.last_name}
        </option>`;
    });

    div.innerHTML = `
        <select name="complainants[${count}][resident_id]"
            class="border p-2 rounded w-full resident-select"
            onchange="fillName(this)">
            ${options}
        </select>

       <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <label class="text-sm font-semibold">First Name</label>
                <input type="text" name="complainants[${count}][first_name]"
                    class="border p-2 rounded w-full"
                    value="${data ? data.first_name : ''}">
            </div>

            <div>
                <label class="text-sm font-semibold">Middle Name</label>
                <input type="text" name="complainants[${count}][middle_name]"
                    class="border p-2 rounded w-full"
                    value="${data ? (data.middle_name ?? '') : ''}">
            </div>

            <div>
                <label class="text-sm font-semibold">Last Name</label>
                <input type="text" name="complainants[${count}][last_name]"
                    class="border p-2 rounded w-full"
                    value="${data ? data.last_name : ''}">
            </div>
        </div>
        <div class="text-right">
            ${count > 0 
                ? `<button type="button" onclick="removeRow(this)"
                    class="text-red-500 hover:text-red-700 text-xl font-bold">&times;</button>`
                : ''}
        </div>
    `;

    container.appendChild(div);

    type==='edit' ? editComplainantCount++ : addComplainantCount++;
}



    // Add Respondent row
    function addRespondent(type='add', data=null){
    const container = type==='edit'
        ? document.getElementById('editRespondentsContainer')
        : document.getElementById('addRespondentsContainer');

    const count = type==='edit' ? editRespondentCount : addRespondentCount;

    const div = document.createElement('div');
    div.classList.add('p-3','border','rounded','space-y-3','bg-gray-50','mb-2'); // ← IMPORTANT

    let options = '<option value="">Select Resident</option>';
    residents.forEach(r => {
        const selected = data && data.resident_id == r.id ? 'selected' : '';
        options += `<option value="${r.id}" ${selected}
            data-first="${r.first_name}"
            data-middle="${r.middle_name}"
            data-last="${r.last_name}">
            ${r.first_name} ${r.middle_name ?? ''} ${r.last_name}
        </option>`;
    });

    div.innerHTML = `
        <select name="respondents[${count}][resident_id]"
            class="border p-2 rounded w-full"
            onchange="fillName(this)">
            ${options}
        </select>

       <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <label class="text-sm font-semibold">First Name</label>
                <input type="text" name="respondents[${count}][first_name]"
                    class="border p-2 rounded w-full"
                    value="${data ? data.first_name : ''}">
            </div>

            <div>
                <label class="text-sm font-semibold">Middle Name</label>
                <input type="text" name="respondents[${count}][middle_name]"
                    class="border p-2 rounded w-full"
                    value="${data ? (data.middle_name ?? '') : ''}">
            </div>

            <div>
                <label class="text-sm font-semibold">Last Name</label>
                <input type="text" name="respondents[${count}][last_name]"
                    class="border p-2 rounded w-full"
                    value="${data ? data.last_name : ''}">
            </div>
        </div>
        <div class="text-right">
            ${count > 0 
                ? `<button type="button" onclick="removeRow(this)"
                    class="text-red-500 hover:text-red-700 text-xl font-bold">&times;</button>`
                : ''}
        </div>
    `;

    container.appendChild(div);

    type==='edit' ? editRespondentCount++ : addRespondentCount++;
}


    // Auto-fill first/middle/last name when dropdown changes
    function fillName(select){
        const option = select.selectedOptions[0];
        const first = option.dataset.first ?? '';
        const middle = option.dataset.middle ?? '';
        const last = option.dataset.last ?? '';

        const inputs = select.parentElement.querySelectorAll('input');
        if(inputs.length === 3){
            inputs[0].value = first;
            inputs[1].value = middle;
            inputs[2].value = last;
        }
    }

    function removeRow(btn){ btn.closest('div.mb-2').remove(); }

    function openAddModal() { document.getElementById('addBlotterModal').classList.remove('hidden'); }
    function closeAddModal() { document.getElementById('addBlotterModal').classList.add('hidden'); }
    function closeViewModal() { document.getElementById('viewBlotterModal').classList.add('hidden'); }
    function closeEditModal() { document.getElementById('editBlotterModal').classList.add('hidden'); }

    function openEditModal(id) {
    fetch(`/secretary/blotter/${id}/edit`)
        .then(res => res.json())
        .then(data => {
            // Set form action and basic fields
            const editForm = document.getElementById('editForm');
            editForm.action = `/secretary/blotter/${id}`;
            document.getElementById('editDate').value = data.date;
            document.getElementById('editTime').value = data.time;
            document.getElementById('editDescription').value = data.description;

            // Clear and populate complainants/respondents
            const compContainer = document.getElementById('editComplainantsContainer');
            const respContainer = document.getElementById('editRespondentsContainer');
            compContainer.innerHTML = '';
            respContainer.innerHTML = '';
            editComplainantCount = 0;
            editRespondentCount = 0;

            data.complainants.forEach(c => addComplainant('edit', c));
            data.respondents.forEach(r => addRespondent('edit', r));

            // ===============================
            // Display existing signed file preview
            // ===============================
            const editSignedContainer = document.getElementById('editSignedContainer');
            const editSignedPreview = document.getElementById('editSignedPreview');
            editSignedPreview.innerHTML = ''; // clear previous preview

            if (data.signed_file) {
                const fileUrl = `/storage/${data.signed_file}`; // public storage path
                const ext = data.signed_file.split('.').pop().toLowerCase();

                if (['jpg','jpeg','png','gif'].includes(ext)) {
                    const img = document.createElement('img');
                    img.src = fileUrl;
                    img.classList.add('max-h-70','mx-auto');
                    editSignedPreview.appendChild(img);
                } else if (ext === 'pdf') {
                    const iframe = document.createElement('iframe');
                    iframe.src = fileUrl;
                    iframe.classList.add('w-full','h-60');
                    editSignedPreview.appendChild(iframe);
                } else {
                    editSignedPreview.innerText = 'Cannot preview this file type.';
                }
                editSignedContainer.style.display = 'block';
            } else {
                editSignedContainer.style.display = 'none';
            }

            // ===============================
            // Show/Hide new file input based on is_signed
            // ===============================
            const editSignedInput = document.getElementById('editSignedInput');

            if (data.is_signed) {
                editSignedInput.parentElement.style.display = 'block';
            } else {
                editSignedInput.parentElement.style.display = 'none';
            }

            // Clear previous selection and preview
            editSignedInput.value = '';
            // Remove any old onchange handler
            editSignedInput.onchange = null;

            // Preview new selected file
            editSignedInput.onchange = function() {
                const file = this.files[0];
                editSignedPreview.innerHTML = '';
                if (!file) return;

                const reader = new FileReader();

                if (file.type.startsWith('image/')) {
                    reader.onload = e => {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.classList.add('max-h-70','mx-auto');
                        editSignedPreview.appendChild(img);
                        editSignedContainer.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                } else if (file.type === 'application/pdf') {
                    reader.onload = e => {
                        const iframe = document.createElement('iframe');
                        iframe.src = e.target.result;
                        iframe.classList.add('w-full','h-60');
                        editSignedPreview.appendChild(iframe);
                        editSignedContainer.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                } else {
                    editSignedPreview.innerText = 'Preview not available for this file type.';
                    editSignedContainer.style.display = 'block';
                }
            };

            // Show the edit modal
            document.getElementById('editBlotterModal').classList.remove('hidden');
        })
        .catch(err => alert('Failed to load blotter details.'));
}



    function formatDateTime(dateStr,timeStr){
        const dateObj = new Date(`${dateStr}T${timeStr}`);
        const options = {year:'numeric', month:'long', day:'numeric'};
        let hours = dateObj.getHours();
        const minutes = dateObj.getMinutes().toString().padStart(2,'0');
        const ampm = hours>=12 ? 'PM':'AM';
        hours = hours%12 || 12;
        return `${dateObj.toLocaleDateString(undefined,options)} ${hours}:${minutes}${ampm.toLowerCase()}`;
    }

    function openViewModal(id){
    fetch(`/secretary/blotter/${id}`)
    .then(res => res.json())
    .then(data => {
        // Date & Time
        document.getElementById('viewDateTime').innerText = formatDateTime(data.date,data.time);

        // Description
        document.getElementById('viewDescription').innerText = data.description;

        // ======================
        // Complainants
        // ======================
        const compList = document.getElementById('viewComplainants');
        compList.innerHTML = '';
        data.complainants.forEach(c=>{
            const li = document.createElement('li');
            li.innerText = `${c.first_name} ${c.middle_name??''} ${c.last_name} (Complainant: ${c.total_as_complainant ?? 0}, Respondent: ${c.total_as_respondent ?? 0})`;
            compList.appendChild(li);
        });

        // ======================
        // Respondents
        // ======================
        const respList = document.getElementById('viewRespondents');
        respList.innerHTML = '';
        data.respondents.forEach(r=>{
            const li = document.createElement('li');
            li.innerText = `${r.first_name} ${r.middle_name??''} ${r.last_name} (Complainant: ${r.total_as_complainant ?? 0}, Respondent: ${r.total_as_respondent ?? 0})`;
            respList.appendChild(li);
        });

        // ======================
        // Signed File Preview
        // ======================
        const signedContainer = document.getElementById('viewSignedContainer');
        const signedPreview = document.getElementById('viewSignedPreview');
        signedPreview.innerHTML = ''; // clear previous

        if(data.signed_file){
            const fileUrl = `/storage/${data.signed_file}`;
            const ext = data.signed_file.split('.').pop().toLowerCase();

            if(['jpg','jpeg','png','gif'].includes(ext)){
                const img = document.createElement('img');
                img.src = fileUrl;
                img.classList.add('max-h-70','mx-auto');
                signedPreview.appendChild(img);
            } else if(ext === 'pdf'){
                const iframe = document.createElement('iframe');
                iframe.src = fileUrl;
                iframe.classList.add('w-full','h-60');
                signedPreview.appendChild(iframe);
            } else {
                signedPreview.innerText = 'Cannot preview this file type.';
            }
            signedContainer.style.display = 'block';
        } else {
            signedContainer.style.display = 'none';
        }

        // Show the modal
        document.getElementById('viewBlotterModal').classList.remove('hidden');
    })
    .catch(err => alert('Failed to load blotter details.'));
}
    function openUploadModal(id){
    document.getElementById('uploadBlotterId').value = id;
    document.getElementById('uploadForm').action = `/secretary/blotter/${id}/upload-signed`;
    document.getElementById('uploadBlotterModal').classList.remove('hidden');
}

function closeUploadModal(){
    document.getElementById('uploadBlotterModal').classList.add('hidden');
}
const signedFileInput = document.querySelector('input[name="signed_file"]');
const previewContainer = document.getElementById('uploadPreviewContainer');
const previewDiv = document.getElementById('uploadPreview');

signedFileInput.addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;

    previewDiv.innerHTML = ''; // clear previous preview

    const fileType = file.type;
    const reader = new FileReader();

    if (fileType.startsWith('image/')) {
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.classList.add('max-h-90', 'mx-auto');
            previewDiv.appendChild(img);
            previewContainer.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else if (fileType === 'application/pdf') {
        reader.onload = function(e) {
            const iframe = document.createElement('iframe');
            iframe.src = e.target.result;
            iframe.classList.add('w-full', 'h-90');
            previewDiv.appendChild(iframe);
            previewContainer.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        previewDiv.innerText = 'Preview not available for this file type.';
        previewContainer.style.display = 'block';
    }
});

</script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/blotter/index.blade.php ENDPATH**/ ?>