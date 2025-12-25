
<?php $__env->startSection('title', 'Document Requests '); ?>
<?php $__env->startSection('content'); ?>
<div class="flex font-sans min-h-screen overflow-hidden">
    
    <div class="w-64 bg-white shadow-lg sticky top-0 h-screen z-20">
        <?php echo $__env->make('partials.captain-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    
    <div class="flex-1 flex flex-col bg-gray-100 h-screen overflow-hidden">
        
        <div class="sticky top-0 z-10 bg-white shadow">
            <?php echo $__env->make('partials.captain-header', ['title' => '📄 Document'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        
        <div class="p-6 overflow-y-auto flex-1">
            <h2 class="text-2xl font-bold text-blue-900 mb-6">Document Requests</h2>



     


            
            
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
    <a href="<?php echo e(route('captain.documents.index', ['document_type' => $docTypeFilter])); ?>"
       class="p-4 rounded shadow border-l-4 text-sm bg-blue-100 border-blue-500 text-blue-700">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="font-semibold">Completed</h4>
                <p class="text-xl font-bold"><?php echo e($counts['completed'] ?? 0); ?></p>
            </div>
            <svg class="h-6 w-6 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
            </svg>
        </div>
    </a>
</div>



<form method="GET" action="<?php echo e(route('captain.documents.index')); ?>" class="flex flex-wrap items-center gap-4 mb-4">



<input type="text" name="search" placeholder="Search by name, code, or type"
       value="<?php echo e(request('search')); ?>"
       class="border px-3 py-2 rounded shadow-sm text-sm focus:ring focus:border-blue-500"
      oninput="clearTimeout(this.delay); this.delay = setTimeout(() => this.form.submit(), 100000)">


<select name="document_type" onchange="this.form.submit()" 
        class="border px-3 py-2 rounded shadow-sm text-sm focus:ring focus:border-blue-500">
    <option value="">Select Document Types</option>
    <?php $__currentLoopData = $documentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($type); ?>" <?php echo e($docTypeFilter == $type ? 'selected' : ''); ?>><?php echo e($type); ?></option>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</select>


<select name="month" onchange="this.form.submit()" 
        class="border px-3 py-2 rounded shadow-sm text-sm focus:ring focus:border-blue-500">
    <option value="">Select Months</option>
    <?php $__currentLoopData = range(1, 12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($m); ?>" <?php echo e(request('month') == $m ? 'selected' : ''); ?>>
            <?php echo e(\Carbon\Carbon::create()->month($m)->format('F')); ?>

        </option>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</select>


<select name="year" onchange="this.form.submit()" 
        class="border px-3 py-2 rounded shadow-sm text-sm focus:ring focus:border-blue-500">
    <option value="">Select Years</option>
    <?php $__currentLoopData = range(date('Y'), date('Y') - 5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($y); ?>" <?php echo e(request('year') == $y ? 'selected' : ''); ?>>
            <?php echo e($y); ?>

        </option>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</select>


<?php if(request('search') || request('document_type') || request('month') || request('year')): ?>
    <a href="<?php echo e(route('captain.documents.index')); ?>" 
       class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded shadow transition text-sm">
        Clear
    </a>
<?php endif; ?>


</form>



<div class="bg-white shadow rounded-lg p-4">
            <div class="overflow-x-auto">
            
<table class="w-full table-auto border text-sm">
    <thead class="bg-blue-600 text-white">
        <tr>
            <th class="px-4 py-3 text-left">#</th>
            <th class="px-4 py-3 text-left">Reference Code</th>
            <th class="px-4 py-3 text-left">Full Name</th>
            <th class="px-4 py-3 text-left">Document Type</th>
            <th class="px-4 py-3 text-left">Requested At</th>
            <th class="px-4 py-3 text-center">Action</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-100">
        <?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr class="<?php echo e($loop->odd ? 'bg-white' : 'bg-gray-100'); ?> border-t">
            <td class="px-4 py-3"><?php echo e($requests->firstItem() + $loop->index); ?></td>
            <td class="px-4 py-3"><?php echo e($request->reference_code ?? 'N/A'); ?></td>
            <td class="px-4 py-3"><?php echo e($request->resident->full_name ?? 'N/A'); ?></td>
<td class="px-4 py-3">
    <?php echo e($request->document_type ?? 'N/A'); ?>

    
    <?php if($request->is_custom_purpose): ?>
        <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full bg-blue-600 text-white">
            Custom
        </span>
    <?php endif; ?>
</td>

           <td class="px-4 py-3">
    <?php echo e($request->created_at ? \Carbon\Carbon::parse($request->created_at)->format('F d, Y | g:i A') : 'N/A'); ?>

</td>

            <td class="px-4 py-3 text-center">
    <button 
        class="view-button inline-flex items-center justify-center gap-1 bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700 transition"
        data-request='<?php echo json_encode($request, 15, 512) ?>'
        title="View Request Details"
    >
        <!-- Eye Icon -->
        <svg xmlns="http://www.w3.org/2000/svg" 
             fill="none" viewBox="0 0 24 24" 
             stroke-width="1.5" stroke="currentColor" 
             class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" 
                  d="M2.25 12s3.75-6.75 9.75-6.75 9.75 6.75 9.75 6.75-3.75 6.75-9.75 6.75S2.25 12 2.25 12z" />
            <path stroke-linecap="round" stroke-linejoin="round" 
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
    
    </button>
</td>

        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr>
            <td colspan="6" class="text-center py-5 text-gray-500">No document requests found.</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>
<div class="mt-6 px-4">
    <?php echo e($requests->withQueryString()->links()); ?>

</div>
            </div>
        </main>
 

    </div>
</div>

<!-- 📄 Document Request Modal -->
<div id="requestDetailsModal" 
     class="fixed inset-0 bg-black bg-opacity-40 hidden z-50 flex items-center justify-center min-h-screen p-4">
    
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl p-8 relative border border-gray-200 transform transition-all">
        
        <!-- Close Button -->
        <button id="closeModalBtn" 
                class="absolute top-4 right-4 text-gray-400 hover:text-red-500 rounded-full p-1 text-2xl font-bold transition">
            &times;
        </button>

        <!-- Header -->
        <div class="mb-6 border-b pb-3 flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6a9 9 0 110 18 9 9 0 010-18z" />
            </svg>
            <h3 class="text-2xl font-bold text-blue-800">Document Request Details</h3>
        </div>

        <!-- Request Info -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
            
            <!-- Reference Code -->
            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                <p class="text-gray-500 font-medium">Reference Code</p>
                <p id="refCode" class="font-semibold text-gray-900">N/A</p>
            </div>

            <!-- Resident Name -->
            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                <p class="text-gray-500 font-medium">Resident Name</p>
                <p id="residentName" class="font-semibold text-gray-900">N/A</p>
            </div>

            <!-- Document Type -->
            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                <p class="text-gray-500 font-medium">Document Type</p>
                <p id="docType" class="font-semibold text-gray-900">N/A</p>
            </div>

            <!-- Purpose -->
            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                <p class="text-gray-500 font-medium">Purpose</p>
                   <span id="purpose" class="text-base font-bold text-gray-900">N/A</span>
                <span id="customPurposeBadge" class="ml-2 px-2 py-1 rounded-full text-xs font-medium bg-blue-600 text-white hidden">
    Custom
</span>

            </div>

            <!-- Price -->
<div class="p-4 bg-gray-50 rounded-lg shadow-sm">
    <p class="text-gray-500 font-medium">Price</p>
    <p class="font-bold text-green-600 flex items-center">
        ₱<span id="priceValue">0.00</span>
      
    </p>
</div>


        
            <!-- Rejection Reason -->
            <div id="rejectionReasonRow" 
                 class="p-4 bg-red-50 rounded-lg shadow-sm hidden col-span-2">
                <p class="text-red-600 font-medium">Rejection Reason</p>
                <p id="rejectionReason" class="font-semibold text-red-700 mt-1"></p>
            </div>

            <!-- Submitted At -->
            <div class="p-4 bg-gray-50 rounded-lg shadow-sm col-span-2">
                <p class="text-gray-500 font-medium">Requested At</p>
                <p id="createdAt" class="font-semibold text-gray-900">N/A</p>
            </div>
            <!-- Completed At -->
<div class="p-4 bg-gray-50 rounded-lg shadow-sm col-span-2">
    <p class="text-gray-500 font-medium">Completed At</p>
    <p id="completedAt" class="font-semibold text-gray-900">N/A</p>
</div>

        </div>
    </div>
</div>



<?php $__env->startPush('scripts'); ?>
<script>
    const modal = document.getElementById('requestDetailsModal');
    const closeModalBtn = document.getElementById('closeModalBtn');

   document.querySelectorAll('.view-button').forEach(btn => {
    btn.addEventListener('click', () => {
        const data = JSON.parse(btn.getAttribute('data-request'));

        document.getElementById('refCode').textContent = data.reference_code ?? 'N/A';
        document.getElementById('residentName').textContent = data.resident?.full_name ?? 'N/A';
        document.getElementById('docType').textContent = data.document_type ?? 'N/A';
        // Purpose
document.getElementById('purpose').textContent = data.purpose ?? 'N/A';

// Show Custom Purpose badge if applicable
const customBadge = document.getElementById('customPurposeBadge');
if (data.is_custom_purpose) {
    customBadge.style.display = 'inline-block';
} else {
    customBadge.style.display = 'none';
}

        // Price
        const priceValueElement = document.getElementById('priceValue');
        priceValueElement.textContent = data.price ? parseFloat(data.price).toFixed(2) : '0.00';

function formatDateWithoutAt(dateStr) {
    if (!dateStr) return 'N/A';
    const d = new Date(dateStr);

    const options = { year: 'numeric', month: 'long', day: '2-digit' };
    const datePart = d.toLocaleDateString('en-US', options);
    
    let hours = d.getHours();
    const minutes = d.getMinutes().toString().padStart(2, '0');
    const ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12 || 12; // convert 0 to 12

    return `${datePart}, ${hours}:${minutes} ${ampm}`;
}

// Created At
document.getElementById('createdAt').textContent = formatDateWithoutAt(data.created_at);

// Completed At
document.getElementById('completedAt').textContent = formatDateWithoutAt(data.completed_at);


        // Rejection Reason
        const rejectionWrapper = document.getElementById('rejectionReasonRow');
        const rejectionText = document.getElementById('rejectionReason');
        if ((data.status ?? '').toLowerCase() === 'rejected') {
            rejectionText.textContent = data.rejection_reason ?? 'No reason provided.';
            rejectionWrapper.classList.remove('hidden');
        } else {
            rejectionText.textContent = '';
            rejectionWrapper.classList.add('hidden');
        }

        modal.classList.remove('hidden');
    });
});

    closeModalBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
    });

    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });

    // --- Search and Filter ---

    let searchTimeout;
const searchInput = document.getElementById('searchInput');
const clearBtn = document.getElementById('clearSearchBtn');

// Show/hide the "X" button
function toggleClearButton() {
    clearBtn.style.display = searchInput.value ? 'block' : 'none';
}

searchInput.addEventListener('input', function () {
    toggleClearButton();

    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        document.getElementById('filterForm').submit();
    }, 500);
});

// Clear input on "X" click
clearBtn.addEventListener('click', function () {
    searchInput.value = '';
    toggleClearButton();
    document.getElementById('filterForm').submit();
});

// On page load
toggleClearButton();


    // Activate default status button (All)
    statusButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            statusButtons.forEach(b => b.classList.remove('active', 'ring-2', 'ring-offset-2', 'ring-blue-500'));
            btn.classList.add('active', 'ring-2', 'ring-offset-2', 'ring-blue-500');
            filterRows();
        });
    });

    // Trigger search on input
    searchInput.addEventListener('input', () => {
        filterRows();
    });

    // Initialize default filter button active
    document.querySelector('.status-filter[data-status="all"]').classList.add('active', 'ring-2', 'ring-offset-2', 'ring-blue-500');



</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/captain/documents/index.blade.php ENDPATH**/ ?>