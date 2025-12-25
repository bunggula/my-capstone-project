

<?php $__env->startSection('content'); ?>
<div class="flex min-h-screen bg-gray-100">

    
    <?php echo $__env->make('partials.captain-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <div class="flex-1 flex flex-col">

        
        <?php echo $__env->make('partials.captain-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="p-8">
          

                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-2xl font-bold text-blue-900">Barangay Blotter Logbook</h1>

                    
                    <button type="button" onclick="openAddModal()"
                            class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800 transition">
                        + Add Record
                    </button>
                </div>
                
                
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
        <table class="w-full table-auto border text-sm">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-4 py-2 text-left">Date & Time</th>
                    <th class="px-4 py-2 text-left">Complainants</th>
                    <th class="px-4 py-2 text-left">Respondents</th>
                    <th class="px-4 py-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $blotters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="<?php echo e($loop->even ? 'bg-gray-100' : 'bg-white'); ?> hover:bg-gray-200">
                        
                        <td class="px-4 py-2">
                            <?php echo e(\Carbon\Carbon::parse($b->date . ' ' . $b->time)->format('F j, Y g:i a')); ?>

                        </td>

                        
                        <td class="px-4 py-2">
                            <ul class="list-disc ml-4 space-y-1">
                                <?php $__currentLoopData = $b->complainants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($c->first_name); ?> <?php echo e($c->middle_name ?? ''); ?> <?php echo e($c->last_name); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </td>

                        
                        <td class="px-4 py-2">
                            <ul class="list-disc ml-4 space-y-1">
                                <?php $__currentLoopData = $b->respondents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($r->first_name); ?> <?php echo e($r->middle_name ?? ''); ?> <?php echo e($r->last_name); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
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
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
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
                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
        </svg>
                            </button>

                            
                            <a href="<?php echo e(route('captain.blotter.print', $b->id)); ?>" target="_blank"
   class="bg-green-600 hover:bg-green-700 text-white p-2 rounded-md transition"
   title="Print">
   <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5h20v5a2 2 0 01-2 2h-2M6 14h12v8H6v-8z"/>
        </svg>
</a>


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
</div>


<div id="addBlotterModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center hidden z-50 p-4">
    <div class="bg-white w-full max-w-3xl rounded-lg shadow-lg p-6 relative overflow-y-auto max-h-[0vh]">

        
        <button onclick="closeAddModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl">&times;</button>

        <h2 class="text-xl font-bold text-blue-900 mb-4">Add Blotter Record</h2>

        <form action="<?php echo e(route('captain.blotter.store')); ?>" method="POST" class="space-y-4">
            <?php echo csrf_field(); ?>

            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium">Date</label>
                    <input type="date" name="date" class="border p-2 w-full rounded" required>
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
                <div id="addComplainantsContainer">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-2">
                        <input type="text" name="complainants[0][first_name]" placeholder="First Name" class="border p-2 rounded" required>
                        <input type="text" name="complainants[0][middle_name]" placeholder="Middle Name" class="border p-2 rounded">
                        <div class="flex items-center gap-2">
                            <input type="text" name="complainants[0][last_name]" placeholder="Last Name" class="border p-2 rounded flex-1" required>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="border rounded p-4">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-semibold text-gray-700">Respondents</h3>
                    <button type="button" onclick="addRespondent()" class="text-sm bg-green-600 text-white px-2 py-1 rounded">+ Add</button>
                </div>
                <div id="addRespondentsContainer">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-2">
                        <input type="text" name="respondents[0][first_name]" placeholder="First Name" class="border p-2 rounded" required>
                        <input type="text" name="respondents[0][middle_name]" placeholder="Middle Name" class="border p-2 rounded">
                        <div class="flex items-center gap-2">
                            <input type="text" name="respondents[0][last_name]" placeholder="Last Name" class="border p-2 rounded flex-1" required>
                        </div>
                    </div>
                </div>
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

     

    </div>
</div>






<div id="editBlotterModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center hidden z-50">
    <div class="bg-white w-full max-w-3xl rounded-lg shadow-lg p-6 relative">
        <button onclick="closeEditModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl">&times;</button>
        <h2 class="text-xl font-bold text-blue-900 mb-4">Edit Blotter Record</h2>

        <form id="editForm" method="POST" class="space-y-4">
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

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeEditModal()" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Cancel</button>
                <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800">Update</button>
            </div>
        </form>
    </div>
</div>


<script>
    // Modal toggles
    function openAddModal() { document.getElementById('addBlotterModal').classList.remove('hidden'); }
    function closeAddModal() { document.getElementById('addBlotterModal').classList.add('hidden'); }
    function closeViewModal() { document.getElementById('viewBlotterModal').classList.add('hidden'); }
    function closeEditModal() { document.getElementById('editBlotterModal').classList.add('hidden'); }

    // View Modal
    function openViewModal(id) {
    fetch(`/captain/blotter/${id}`)
    .then(res => res.json())
    .then(data => {
        // Format combined Date + Time
        document.getElementById('viewDateTime').innerText = formatDateTime(data.date, data.time);

        // Description
        document.getElementById('viewDescription').innerText = data.description;

        // Complainants
        const compList = document.getElementById('viewComplainants');
        compList.innerHTML = '';
        data.complainants.forEach(c => {
            const li = document.createElement('li');
            li.innerText = `${c.first_name} ${c.middle_name ? c.middle_name + ' ' : ''}${c.last_name}`;
            compList.appendChild(li);
        });

        // Respondents
        const respList = document.getElementById('viewRespondents');
        respList.innerHTML = '';
        data.respondents.forEach(r => {
            const li = document.createElement('li');
            li.innerText = `${r.first_name} ${r.middle_name ? r.middle_name + ' ' : ''}${r.last_name}`;
            respList.appendChild(li);
        });

        // Show modal
        document.getElementById('viewBlotterModal').classList.remove('hidden');
    })
    .catch(err => alert('Failed to load blotter details.'));
}

    // Counters
    let addComplainantCount = 1, addRespondentCount = 1;
    let editComplainantCount = 0, editRespondentCount = 0;

    // Dynamic Add Fields (both Add & Edit)
    function addComplainant(type='add', data=null){
        const container = type==='edit' ? document.getElementById('editComplainantsContainer') : document.getElementById('addComplainantsContainer');
        const count = type==='edit' ? editComplainantCount : addComplainantCount;

        const div = document.createElement('div');
        div.classList.add('grid', 'grid-cols-1', 'sm:grid-cols-3', 'gap-3', 'mb-2');
        div.innerHTML = `
            <input type="text" name="complainants[${count}][first_name]" placeholder="First Name" class="border p-2 rounded" value="${data?.first_name??''}" required>
            <input type="text" name="complainants[${count}][middle_name]" placeholder="Middle Name" class="border p-2 rounded" value="${data?.middle_name??''}">
            <div class="flex items-center gap-2">
                <input type="text" name="complainants[${count}][last_name]" placeholder="Last Name" class="border p-2 rounded flex-1" value="${data?.last_name??''}" required>
                <button type="button" onclick="removeRow(this)" class="text-red-500 hover:text-red-700 text-xl font-bold">&times;</button>
            </div>
        `;
        container.appendChild(div);

        if(type==='edit') editComplainantCount++; else addComplainantCount++;
    }

    function addRespondent(type='add', data=null){
        const container = type==='edit' ? document.getElementById('editRespondentsContainer') : document.getElementById('addRespondentsContainer');
        const count = type==='edit' ? editRespondentCount : addRespondentCount;

        const div = document.createElement('div');
        div.classList.add('grid', 'grid-cols-1', 'sm:grid-cols-3', 'gap-3', 'mb-2');
        div.innerHTML = `
            <input type="text" name="respondents[${count}][first_name]" placeholder="First Name" class="border p-2 rounded" value="${data?.first_name??''}" required>
            <input type="text" name="respondents[${count}][middle_name]" placeholder="Middle Name" class="border p-2 rounded" value="${data?.middle_name??''}">
            <div class="flex items-center gap-2">
                <input type="text" name="respondents[${count}][last_name]" placeholder="Last Name" class="border p-2 rounded flex-1" value="${data?.last_name??''}" required>
                <button type="button" onclick="removeRow(this)" class="text-red-500 hover:text-red-700 text-xl font-bold">&times;</button>
            </div>
        `;
        container.appendChild(div);

        if(type==='edit') editRespondentCount++; else addRespondentCount++;
    }

    // Remove dynamic row
    function removeRow(btn){
        btn.closest('div.mb-2').remove();
    }

    // Edit Modal
    function openEditModal(id){
        fetch(`/captain/blotter/${id}/edit`)
        .then(res=>res.json())
        .then(data=>{
            document.getElementById('editForm').action = `/captain/blotter/${id}`;
            document.getElementById('editDate').value = data.date;
            document.getElementById('editTime').value = data.time;
            document.getElementById('editDescription').value = data.description;

            const compContainer = document.getElementById('editComplainantsContainer');
            const respContainer = document.getElementById('editRespondentsContainer');
            compContainer.innerHTML = ''; respContainer.innerHTML = '';
            editComplainantCount = 0; editRespondentCount = 0;

            data.complainants.forEach(c => addComplainant('edit', c));
            data.respondents.forEach(r => addRespondent('edit', r));

            document.getElementById('editBlotterModal').classList.remove('hidden');
        })
        .catch(err => alert('Failed to load blotter details.'));
    }

// Format date & time as "September 20, 2025 9:00am"
function formatDateTime(dateStr, timeStr) {
    const dateObj = new Date(`${dateStr}T${timeStr}`);
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    let hours = dateObj.getHours();
    const minutes = dateObj.getMinutes().toString().padStart(2, '0');
    const ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12 || 12;
    return `${dateObj.toLocaleDateString(undefined, options)} ${hours}:${minutes}${ampm.toLowerCase()}`;
}

// Example usage to populate modal dynamically
// document.getElementById('viewDateTime').innerText = formatDateTime('2025-09-20', '09:00');

</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/captain/blotter/index.blade.php ENDPATH**/ ?>