
<?php $__env->startSection('title', 'Document Requests '); ?>
<?php $__env->startSection('content'); ?>
<div class="flex font-sans min-h-screen overflow-hidden">
    
    <div class="w-64 bg-white shadow-lg sticky top-0 h-screen z-20">
        <?php echo $__env->make('partials.secretary-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    
    <div class="flex-1 flex flex-col bg-gray-100 h-screen overflow-hidden">
        
        <div class="sticky top-0 z-10 bg-white shadow">
            <?php echo $__env->make('partials.secretary-header', ['title' => '📄 Document Requests'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        
        <div class="p-6 overflow-y-auto flex-1">
            <h2 class="text-2xl font-bold text-blue-900 mb-6">Document Requests</h2>
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




   <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
    
    <a href="<?php echo e(route('secretary.document_requests.index', array_merge(request()->all(), ['status' => 'pending']))); ?>"
       class="p-4 rounded shadow border-l-4 text-sm <?php echo e($currentStatus === 'pending' ? 'bg-yellow-200 border-yellow-600 text-yellow-900' : 'bg-yellow-100 border-yellow-500 text-yellow-700'); ?>">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="font-semibold">Pending</h4>
                <p class="text-xl font-bold"><?php echo e($counts['pending'] ?? 0); ?></p>
            </div>
            <svg class="h-6 w-6 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" />
            </svg>
        </div>
    </a>

    
    <a href="<?php echo e(route('secretary.document_requests.index', array_merge(request()->all(), ['status' => 'approved']))); ?>"
       class="p-4 rounded shadow border-l-4 text-sm <?php echo e($currentStatus === 'approved' ? 'bg-green-200 border-green-600 text-green-900' : 'bg-green-100 border-green-500 text-green-700'); ?>">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="font-semibold">Approved</h4>
                <p class="text-xl font-bold"><?php echo e($counts['approved'] ?? 0); ?></p>
            </div>
            <svg class="h-6 w-6 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
    </a>

    
    <a href="<?php echo e(route('secretary.document_requests.index', array_merge(request()->all(), ['status' => 'rejected']))); ?>"
       class="p-4 rounded shadow border-l-4 text-sm <?php echo e($currentStatus === 'rejected' ? 'bg-red-200 border-red-600 text-red-900' : 'bg-red-100 border-red-500 text-red-700'); ?>">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="font-semibold">Rejected</h4>
                <p class="text-xl font-bold"><?php echo e($counts['rejected'] ?? 0); ?></p>
            </div>
            <svg class="h-6 w-6 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </div>
    </a>

    
    <a href="<?php echo e(route('secretary.document_requests.index', array_merge(request()->all(), ['status' => 'ready_for_pickup']))); ?>"
       class="p-4 rounded shadow border-l-4 text-sm <?php echo e($currentStatus === 'ready_for_pickup' ? 'bg-purple-200 border-purple-600 text-purple-900' : 'bg-purple-100 border-purple-500 text-purple-700'); ?>">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="font-semibold">Ready for Pickup</h4>
                <p class="text-xl font-bold"><?php echo e($counts['ready_for_pickup'] ?? 0); ?></p>
            </div>
            <svg class="h-6 w-6 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6" />
            </svg>
        </div>
    </a>

    
    <a href="<?php echo e(route('secretary.document_requests.index', array_merge(request()->all(), ['status' => 'completed']))); ?>"
       class="p-4 rounded shadow border-l-4 text-sm <?php echo e($currentStatus === 'completed' ? 'bg-blue-200 border-blue-600 text-blue-900' : 'bg-blue-100 border-blue-500 text-blue-700'); ?>">
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



<form method="GET" action="<?php echo e(route('secretary.document_requests.index')); ?>"
      id="autoSearchForm"
      class="mb-4 flex flex-wrap items-center gap-4 w-full">

    <!-- Search Bar with Clear Button -->
    <div class="relative flex items-center w-80">
        <input 
            type="text" 
            name="search" 
            id="searchInput"
            placeholder="Search..." 
            value="<?php echo e(request('search')); ?>" 
            class="border border-gray-300 rounded-md px-4 py-2 w-full pr-10 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
            oninput="handleInput()">

        <button 
            type="button" 
            id="clearSearch"
            class="hidden absolute right-3 text-gray-400 hover:text-gray-600"
            onclick="clearSearchInput()">
            ✕
        </button>
    </div>

    <!-- Document Type Dropdown -->
    <div class="relative w-64">
        <select name="document_type" id="document_type"
                class="border border-gray-300 rounded-md px-4 py-2 w-full bg-white text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none truncate"
                onchange="this.form.submit()"
                style="text-overflow: ellipsis; white-space: nowrap; overflow: hidden;">
            <option value="">--Select Document Types --</option>

            <?php $__currentLoopData = $documentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($type); ?>" 
                    <?php echo e(request('document_type') == $type ? 'selected' : ''); ?>

                    class="truncate">
                    <?php echo e($type); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    <!-- Clear Filters Button -->
    <?php if(request('search') || request('document_type')): ?>
        <button 
            type="button"
            onclick="clearAllFilters()"
            class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 text-sm shadow-sm">
            Clear
        </button>
    <?php endif; ?>
<!-- Scan QR Button -->
<button type="button"
        onclick="openQRScanner()"
        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm shadow-sm">
    Scan QR
</button>

</form>


<script>
function handleInput() {
    const input = document.getElementById('searchInput');
    const clearBtn = document.getElementById('clearSearch');

    clearBtn.classList.toggle('hidden', input.value.trim() === '');

    clearTimeout(input.delay);
    input.delay = setTimeout(() => {
        input.form.submit();
    }, 100000);
}

function clearSearchInput() {
    const input = document.getElementById('searchInput');
    input.value = '';
    document.getElementById('clearSearch').classList.add('hidden');
    document.getElementById('autoSearchForm').submit();
}

// CLEAR ALL FILTERS
function clearAllFilters() {
    document.getElementById('searchInput').value = '';
    document.querySelector('[name=document_type]').value = '';
    document.getElementById('autoSearchForm').submit();
}

window.addEventListener('load', handleInput);
</script>



            
            <div class="bg-white shadow rounded-lg p-4">
                        <div class="overflow-x-auto">
        <table class="w-full table-auto border text-sm text-left">
           <thead class="bg-blue-600 text-white uppercase text-xs">
    <tr>
        <th class="px-4 py-3 text-left">#</th>
        <th class="px-4 py-3 text-left">Reference Code</th>
        <th class="px-4 py-3 text-left">Resident</th>
        <th class="px-4 py-3 text-left">Document Type</th>
        <th class="px-4 py-3 text-left">Requested Type</th>
        <th class="px-4 py-3 text-left">Requested At</th>
        <th class="px-4 py-3 text-left">Status</th>
        <th class="px-4 py-3 text-left">Action</th>
    </tr>
</thead>
<tbody class="divide-y divide-gray-100">
    <?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <tr class="<?php echo e($loop->even ? 'bg-gray-100' : 'bg-white'); ?> hover:bg-gray-200">
        <td class="px-4 py-2"><?php echo e($requests->firstItem() + $loop->index); ?></td>
        <td class="px-4 py-2"><?php echo e($request->reference_code ?? 'N/A'); ?></td>
        <td class="px-4 py-2"><?php echo e($request->resident->full_name ?? 'N/A'); ?></td>
        <td class="px-4 py-2"><?php echo e($request->document_type); ?></td>
       <td class="px-4 py-2">
    <?php
        $type = $request->resident->type ?? null;
        $typeLabel = match($type) {
            'walk-in' => 'Walk-in',
            'online' => 'Online',
            default => 'Online',
        };
    ?>
    <?php echo e($typeLabel); ?>

</td>

        <td class="px-4 py-2"><?php echo e(\Carbon\Carbon::parse($request->created_at)->format('F d, Y | g:i A')); ?></td>
        <td class="px-4 py-2">
            <span class="px-2 py-1 rounded text-xs font-medium
                <?php if($request->status === 'pending'): ?> bg-yellow-100 text-yellow-700
                <?php elseif($request->status === 'approved'): ?> bg-green-100 text-green-700
                <?php elseif($request->status === 'rejected'): ?> bg-red-100 text-red-700
                <?php elseif($request->status === 'ready_for_pickup'): ?> bg-purple-100 text-purple-700
                <?php elseif($request->status === 'completed'): ?> bg-blue-100 text-blue-700
                <?php endif; ?>">
                <?php echo e(ucfirst(str_replace('_', ' ', $request->status))); ?>

            </span>
        </td>
                               <td class="px-4 py-2 flex flex-wrap gap-2">
                               
                               <button type="button"
    onclick="showRequestDetails(<?php echo e($request->toJson()); ?>)"
      class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs flex items-center justify-center"
    title="View Request Details">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7
                     -1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
    </svg>
</button>

<?php if($request->status === 'pending'): ?>
    
    <form method="POST" action="<?php echo e(route('secretary.document_requests.update_status', ['id' => $request->id, 'status' => 'approved'])); ?>" class="approve-form">
    <?php echo csrf_field(); ?>
    <button type="button"
    onclick="confirmApprove(this)"
    data-resident="<?php echo e($request->resident->full_name); ?>"
    data-document="<?php echo e($request->document_type); ?>"
    data-is-custom="<?php echo e($request->is_custom_purpose ? 'true' : 'false'); ?>"
    data-price="<?php echo e($request->price ?? 0); ?>"
    title="Approve"
    class="bg-green-600 hover:bg-green-700 text-white p-2 rounded transition">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
    </svg>
</button>

</form>


    
    <form method="POST" action="<?php echo e(route('secretary.document_requests.update_status', ['id' => $request->id, 'status' => 'rejected'])); ?>" class="reject-form">
    <?php echo csrf_field(); ?>
    <button type="button"
        onclick="confirmReject(this, '<?php echo e($request->resident->full_name); ?>', '<?php echo e($request->document_type); ?>')"
        title="Reject"
        class="bg-red-600 hover:bg-red-700 text-white p-2 rounded transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</form>



        <?php elseif($request->status === 'approved'): ?>
    
      <a href="<?php echo e(route('secretary.document_requests.print', $request->id)); ?>"
        title="Print"
        target="_blank" 
        class="bg-green-600 hover:bg-green-700 text-white p-2 rounded transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 14h12v8H6v-8z" />
        </svg>
    </a>

        
        <form method="POST" action="<?php echo e(route('secretary.document_requests.update_status', ['id' => $request->id, 'status' => 'ready_for_pickup'])); ?>" class="pickup-form">
    <?php echo csrf_field(); ?>
    <button type="button"
        onclick="confirmReadyForPickup(this, '<?php echo e($request->resident->full_name); ?>', '<?php echo e($request->document_type); ?>')"
        title="Ready for Pickup"
        class="bg-purple-600 hover:bg-purple-700 text-white p-2 rounded transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-18 0v8a2 2 0 002 2h14a2 2 0 002-2V8m-18 0L12 3l9 5" />
        </svg>
    </button>
</form>

        <?php elseif($request->status === 'ready_for_pickup'): ?>
    
    <a href="<?php echo e(route('secretary.document_requests.print', $request->id)); ?>" title="Print" target="_blank"
        class="bg-green-600 hover:bg-green-700 text-white p-2 rounded transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 14h12v8H6v-8z" />
        </svg>
    </a>

        
        <form method="POST" action="<?php echo e(route('secretary.document_requests.mark_completed', $request->id)); ?>" class="complete-form">
    <?php echo csrf_field(); ?>
    <button type="button"
        onclick="confirmComplete(this, '<?php echo e($request->resident->full_name); ?>', '<?php echo e($request->document_type); ?>')"
        title="Mark as Completed"
        class="bg-green-600 hover:bg-green-700 text-white p-2 rounded transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
    </button>
</form>

    <?php elseif($request->status === 'completed'): ?>
        <span class="text-gray-500 italic text-sm"></span>
    <?php endif; ?>
</td>


                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="px-4 py-6 text-center text-gray-500">
                                    No document requests found.
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <div class="mt-4">
    <?php echo e($requests->withQueryString()->links()); ?>

</div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- 📄 Document Request Details Modal -->
<div id="requestDetailsModal" 
     class="fixed inset-0 bg-black bg-opacity-40 backdrop-blur-sm hidden justify-center items-center z-50 overflow-auto p-4">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl p-8 relative border border-gray-200 transform transition-all">
        
        <!-- Close Button -->
        <button onclick="closeModal()" 
                class="absolute top-4 right-4 text-gray-500 hover:text-red-500 transition text-3xl font-bold leading-none"
                title="Close">
            &times;
        </button>

        <!-- Modal Header -->
        <div class="mb-6 border-b pb-4 flex items-center gap-2">
            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" 
                      d="M12 6v6l4 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="text-2xl font-bold text-blue-800">Document Request Details</h3>
        </div>

        <!-- Request Info -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm sm:text-base text-gray-700">
            
            <!-- Reference Code -->
            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                <p class="text-gray-500 font-semibold">Reference Code</p>
                <p id="refCode" class="text-base font-bold text-gray-900">N/A</p>
            </div>

            <!-- Resident Name -->
            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                <p class="text-gray-500 font-semibold">Resident Name</p>
                <p id="residentName" class="text-base font-bold text-gray-900">N/A</p>
            </div>

            <!-- Document Type -->
            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                <p class="text-gray-500 font-semibold">Document Type</p>
                <p id="docType" class="text-base font-bold text-gray-900">N/A</p>
            </div>

         <!-- Purpose -->
 <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
    <p class="text-gray-500 font-semibold">Purpose</p>
    <span id="purpose" class="text-base font-bold text-gray-900">N/A</span>

    <!-- Custom Purpose Badge -->
    <span id="customPurposeBadge" 
          class="ml-2 px-2 py-1 rounded-full text-xs font-medium bg-blue-600 text-white hidden">
        Custom
    </span>
</div>


            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
    <p class="text-gray-500 font-semibold">Price</p>
    <p id="price" class="text-lg font-extrabold text-green-600">
        ₱<span id="modalPrice">0.00</span>
       
    </p>
</div>


            <!-- Status Badge -->
            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                <p class="text-gray-500 font-semibold">Status</p>
                <span id="statusBadge" 
                      class="inline-block px-3 py-1 rounded-full text-sm font-medium mt-1">
                    <span id="status">N/A</span>
                </span>
            </div>

            <!-- Rejection Reason (conditionally shown) -->
            <div id="rejectionReasonRow" class="p-4 bg-red-50 rounded-lg shadow-sm hidden col-span-2">
                <p class="text-red-600 font-semibold">Rejection Reason</p>
                <p id="rejectionReason" class="text-red-700 font-medium mt-1"></p>
            </div>

            <!-- Submitted At -->
           <div class="p-4 bg-gray-50 rounded-lg shadow-sm col-span-2">
    <p class="text-gray-500 font-semibold">Submitted At</p>
    <p id="createdAt" class="text-base font-bold text-gray-900">N/A</p>
</div>
<!-- Completed At -->
<div id="completedAtRow" class="p-4 bg-gray-50 rounded-lg shadow-sm col-span-2">
    <p class="text-gray-500 font-semibold">Completed At</p>
    <p id="completedAt" class="text-base font-bold text-gray-900">N/A</p>
</div>

        </div>
    </div>
</div>

<!-- QR Scanner Modal -->
<div id="qrScannerModal"
     class="fixed inset-0 bg-black bg-opacity-40 backdrop-blur-sm hidden justify-center items-center z-50 p-4">

    <div class="bg-white p-6 rounded-xl shadow-xl w-full max-w-md relative">
        
        <!-- Close Button -->
        <button onclick="closeQRScanner()"
                class="absolute top-3 right-3 text-gray-500 hover:text-red-600 text-xl font-bold">&times;</button>

        <h2 class="text-lg font-bold text-blue-700 mb-4">Scan QR Code</h2>

        <div id="qr-reader" class="w-full"></div>
    </div>
</div>

<!-- QR Scanner Library -->
<script src="https://unpkg.com/html5-qrcode"></script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  let qrScanner = null;

function openQRScanner() {
    document.getElementById('qrScannerModal').classList.remove('hidden');
    document.getElementById('qrScannerModal').classList.add('flex');

    qrScanner = new Html5Qrcode("qr-reader");

    qrScanner.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: 250 },
        qrData => {

            // Extract code even if QR contains full URL
            let code = qrData.includes("/")
                ? qrData.split("/").pop()
                : qrData;

            // Auto-fill the search input
            document.getElementById('searchInput').value = code;

            // Auto-submit the search form
            document.getElementById('autoSearchForm').submit();

            // Stop scanner + close modal
            qrScanner.stop();
            closeQRScanner();
        }
    ).catch(err => {
        console.error("QR Scanner failed:", err);
    });
}

function closeQRScanner() {
    document.getElementById('qrScannerModal').classList.add('hidden');
    document.getElementById('qrScannerModal').classList.remove('flex');

    if (qrScanner) {
        qrScanner.stop().then(() => qrScanner.clear());
    }
}

function showRequestDetails(request) {
    // Set text details
    document.getElementById('refCode').innerText = request.reference_code || 'N/A';
    document.getElementById('residentName').innerText = request.resident?.full_name || 'N/A';
    document.getElementById('docType').innerText = request.document_type || 'N/A';
   // Submitted At
if (request.created_at) {
    const date = new Date(request.created_at);

    // Format date
    const dateOptions = { year: 'numeric', month: 'long', day: 'numeric' };
    const formattedDate = date.toLocaleDateString('en-US', dateOptions);

    // Format time
    const timeOptions = { hour: 'numeric', minute: '2-digit', hour12: true };
    const formattedTime = date.toLocaleTimeString('en-US', timeOptions);

    document.getElementById('createdAt').innerText = `${formattedDate} ${formattedTime}`;
} else {
    document.getElementById('createdAt').innerText = 'N/A';
}
  // Completed At
const completedAtRow = document.getElementById('completedAtRow');
if (request.status === 'completed' && request.completed_at) {
    completedAtRow.style.display = 'block';

    const date = new Date(request.completed_at);

    // Format date
    const dateOptions = { year: 'numeric', month: 'long', day: 'numeric' };
    const formattedDate = date.toLocaleDateString('en-US', dateOptions);

    // Format time
    const timeOptions = { hour: 'numeric', minute: '2-digit', hour12: true };
    const formattedTime = date.toLocaleTimeString('en-US', timeOptions);

    document.getElementById('completedAt').innerText = `${formattedDate}, ${formattedTime}`;
} else {
    completedAtRow.style.display = 'none';
}

document.getElementById('purpose').textContent = request.purpose ?? 'N/A';

// Show Custom Purpose badge
const customBadge = document.getElementById('customPurposeBadge');
if (request.is_custom_purpose) {
    customBadge.style.display = 'inline-block';
} else {
    customBadge.style.display = 'none';
}
 // Price + Paid badge
document.getElementById('modalPrice').textContent = Number(request.price ?? 0).toFixed(2);
const badge = document.getElementById('modalPaidBadge');



    // Show/hide Rejection Reason
    const rejectionReasonRow = document.getElementById('rejectionReasonRow');
    if (request.status === 'rejected') {
        rejectionReasonRow.style.display = 'block';
        document.getElementById('rejectionReason').innerText = request.rejection_reason ?? 'N/A';
    } else {
        rejectionReasonRow.style.display = 'none';
    }

    // Status badge with colors
    const statusText = request.status.replaceAll('_', ' ').toUpperCase();
    let statusClass = 'bg-gray-100 text-gray-700';

    switch (request.status) {
        case 'pending':
            statusClass = 'bg-yellow-100 text-yellow-700';
            break;
        case 'approved':
            statusClass = 'bg-green-100 text-green-700';
            break;
        case 'rejected':
            statusClass = 'bg-red-100 text-red-700';
            break;
        case 'ready_for_pickup':
            statusClass = 'bg-purple-100 text-purple-700';
            break;
        case 'completed':
            statusClass = 'bg-blue-100 text-blue-700';
            break;
    }

    document.getElementById('status').innerHTML = `
        <span class="px-2 py-1 rounded text-xs font-medium ${statusClass}">
            ${statusText}
        </span>
    `;

    // Show modal
    document.getElementById('requestDetailsModal').classList.remove('hidden');
    document.getElementById('requestDetailsModal').classList.add('flex');
}




function closeModal() {
    document.getElementById('requestDetailsModal').classList.add('hidden');
    document.getElementById('requestDetailsModal').classList.remove('flex');
}
function confirmApprove(button) {
    const residentName = button.dataset.resident;
    const documentType = button.dataset.document;
    const isCustom = button.dataset.isCustom === 'true';
    let price = Number(button.dataset.price); // convert sa number

    // Kung custom purpose at walang valid price
    if (isCustom && (!price || price <= 0)) {
        Swal.fire({
            title: 'Set Price First',
            html: `<p>Resident: <b>${residentName}</b></p>
                   <p>Document Type: <b>${documentType}</b></p>`,
            input: 'number',
            inputLabel: 'Enter price for this custom purpose',
            inputAttributes: { min: 0 },
            inputPlaceholder: 'Enter price here...',
            showCancelButton: true,
            confirmButtonText: 'Save & Approve',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#16a34a',
            preConfirm: (value) => {
                if (!value || Number(value) <= 0) {
                    Swal.showValidationMessage('Please enter a valid price!');
                }
                return Number(value);
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = button.closest('form');

                // Remove existing hidden input kung meron
                const existingInput = form.querySelector('input[name="price"]');
                if (existingInput) existingInput.remove();

                // Gumawa ng bagong hidden input
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'price';
                input.value = result.value;
                form.appendChild(input);

                form.submit(); // submit pagkatapos ma-append ang price
            }
        });
        return; // stop normal flow
    }

    // Normal approval kung may valid price o hindi custom
    Swal.fire({
        title: 'Approve Request?',
        html: `Are you sure you want to approve <b>${documentType}</b> for <b>${residentName}</b>?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, approve it!'
    }).then((result) => {
        if (result.isConfirmed) {
            button.closest('form').submit();
        }
    });
}


function confirmReject(button, residentName, documentType) {
    Swal.fire({
        title: `Reject Request for ${residentName}?`,
        html: `Select a reason to reject <b>${documentType}</b> for <b>${residentName}</b>.`,
        input: 'select',
        inputOptions: {
            'Incomplete Requirements': 'Incomplete Requirements',
            'Invalid Information Provided': 'Invalid Information Provided',
            'Duplicate Request': 'Duplicate Request',
            'Not a Resident of Barangay': 'Not a Resident of Barangay',
            'Other': 'Other'
        },
        inputPlaceholder: 'Select a reason',
        showCancelButton: true,
        confirmButtonText: 'Next',
        preConfirm: (value) => {
            if (!value) {
                Swal.showValidationMessage('You must select a reason');
            }
            return value;
        }
    }).then((result) => {
        if (!result.isConfirmed) return;

        const reason = result.value;

        // If selected reason is "Other", ask for custom input
        if (reason === 'Other') {
            Swal.fire({
                title: 'Enter Reason',
                input: 'text',
                inputPlaceholder: 'Type the rejection reason here...',
                showCancelButton: true,
                confirmButtonText: 'Submit',
                preConfirm: (customReason) => {
                    if (!customReason.trim()) {
                        Swal.showValidationMessage('You must enter a reason');
                    }
                    return customReason;
                }
            }).then((result2) => {
                if (result2.isConfirmed) {
                    submitRejectionForm(button, result2.value);
                }
            });
        } else {
            // Directly submit with selected reason
            submitRejectionForm(button, reason);
        }
    });
}

function submitRejectionForm(button, reason) {
    const form = button.closest('form');
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'rejection_reason';
    input.value = reason;
    form.appendChild(input);
    form.submit();
}

function confirmComplete(button, residentName, documentType) {
    Swal.fire({
        title: `Mark as Completed?`,
        html: `Are you sure you want to mark <b>${documentType}</b> for <b>${residentName}</b> as completed?`,
        icon: 'success',
        showCancelButton: true,
        confirmButtonColor: '#22c55e',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, complete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            button.closest('form').submit();
        }
    });
}
  

    <?php if(session('success')): ?>
    Swal.fire({
        title: 'Success!',
        text: "<?php echo e(session('success')); ?>",
        icon: 'success',
        confirmButtonText: 'OK'
    });
    <?php endif; ?>

    <?php if(session('error')): ?>
    Swal.fire({
        title: 'Error',
        text: "<?php echo e(session('error')); ?>",
        icon: 'error',
        confirmButtonText: 'OK'
    });
    <?php endif; ?>
    function confirmReadyForPickup(button, residentName, documentType) {
    Swal.fire({
        title: `Mark as Ready for Pickup?`,
        html: `Are you sure you want to mark <b>${documentType}</b> for <b>${residentName}</b> as ready for pickup?`,
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, mark it!'
    }).then((result) => {
        if (result.isConfirmed) {
            button.closest('form').submit();
        }
    });
}

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/document_requests/index.blade.php ENDPATH**/ ?>