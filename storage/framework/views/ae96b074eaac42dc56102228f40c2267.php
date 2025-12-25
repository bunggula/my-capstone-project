

<?php $__env->startSection('title', 'Residents'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex h-screen bg-gray-100 overflow-hidden">
    <?php echo $__env->make('partials.captain-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1 flex flex-col overflow-hidden">
        <?php echo $__env->make('partials.captain-header', ['title' => 'Residents'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="flex-1 overflow-y-auto p-6">
           

    <div class="flex-1 bg-gray-100 min-h-screen p-6">
        <h2 class="text-2xl font-semibold text-blue-800 mb-6 flex items-center gap-2">
            Resident Concerns
        </h2>

        


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





        
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
           <?php
    $filters = request()->except('status'); // Preserve all query parameters except 'status'
?>

<a href="<?php echo e(route('captain.concerns.index', array_merge($filters, ['status' => 'pending']))); ?>"
   class="block p-4 rounded shadow border-l-4 <?php echo e($currentStatus === 'pending' ? 'bg-yellow-200 border-yellow-600 text-yellow-900' : 'bg-yellow-100 border-yellow-500 text-yellow-700'); ?>">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="font-semibold text-sm">Pending Concerns</h4>
            <p class="text-2xl font-bold"><?php echo e($pendingCount); ?></p>
        </div>
        <svg class="h-8 w-8 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>
</a>

<a href="<?php echo e(route('captain.concerns.index', array_merge($filters, ['status' => 'on going']))); ?>"
   class="block p-4 rounded shadow border-l-4 <?php echo e($currentStatus === 'on going' ? 'bg-blue-200 border-blue-600 text-blue-900' : 'bg-blue-100 border-blue-500 text-blue-700'); ?>">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="font-semibold text-sm">On Going Concerns</h4>
            <p class="text-2xl font-bold"><?php echo e($onGoingCount); ?></p>
        </div>
        <svg class="h-8 w-8 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m9-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>
</a>

<a href="<?php echo e(route('captain.concerns.index', array_merge($filters, ['status' => 'resolved']))); ?>"
   class="block p-4 rounded shadow border-l-4 <?php echo e($currentStatus === 'resolved' ? 'bg-green-200 border-green-600 text-green-900' : 'bg-green-100 border-green-500 text-green-700'); ?>">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="font-semibold text-sm">Resolved Concerns</h4>
            <p class="text-2xl font-bold"><?php echo e($resolvedCount); ?></p>
        </div>
        <svg class="h-8 w-8 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
    </div>
</a>

<a href="<?php echo e(route('captain.concerns.index', $filters)); ?>"
   class="block p-4 rounded shadow border-l-4 <?php echo e(is_null($currentStatus) ? 'bg-gray-200 border-gray-600 text-gray-900' : 'bg-gray-100 border-gray-400 text-gray-700'); ?>">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="font-semibold text-sm">All</h4>
            <p class="text-2xl font-bold"><?php echo e($totalCount); ?></p>
        </div>
        <svg class="h-8 w-8 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2M3 20h18M9 10h6M9 14h6"/>
        </svg>
    </div>
</a>

        </div>

        

<form method="GET" action="<?php echo e(route('captain.concerns.index')); ?>" id="concernSearchForm" class="mb-4 flex flex-wrap items-center gap-3">



<div class="relative w-full sm:w-48">
    <input
        type="text"
        name="search"
        value="<?php echo e(request('search')); ?>"
        placeholder="Search title or name..."
        class="w-full px-3 py-2 pr-8 rounded border border-gray-300 text-sm text-blue-900 focus:ring focus:ring-blue-200"
       oninput="clearTimeout(this.delay); this.delay = setTimeout(() => this.form.submit(), 100000)">
    
    <?php if(request('search')): ?>
        <button 
            type="button"
            onclick="document.querySelector('input[name=search]').value=''; document.getElementById('concernSearchForm').submit();"
            class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-600 text-base font-bold focus:outline-none">
            &times;
        </button>
    <?php endif; ?>
</div>


<select 
    name="month" 
    class="border border-gray-300 rounded px-3 py-2 text-sm focus:ring focus:border-blue-500"
    onchange="document.getElementById('concernSearchForm').submit();"
>
    <option value="">Select Months</option>
    <?php $__currentLoopData = range(1, 12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($m); ?>" <?php echo e(request('month') == $m ? 'selected' : ''); ?>>
            <?php echo e(\Carbon\Carbon::create()->month($m)->format('F')); ?>

        </option>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</select>


<select 
    name="year" 
    class="border border-gray-300 rounded px-3 py-2 text-sm focus:ring focus:border-blue-500"
    onchange="document.getElementById('concernSearchForm').submit();"
>
    <option value="">Select Years</option>
    <?php $__currentLoopData = range(date('Y'), date('Y') - 5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($y); ?>" <?php echo e(request('year') == $y ? 'selected' : ''); ?>>
            <?php echo e($y); ?>

        </option>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</select>


<?php if(request('search') || request('month') || request('year')): ?>
    <a href="<?php echo e(route('captain.concerns.index')); ?>" 
       class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded text-sm shadow transition">
        Clear
    </a>
<?php endif; ?>


</form>



        <?php if($concerns->isEmpty()): ?>
            <div class="bg-white p-6 rounded shadow text-gray-500">
                No concerns submitted yet.
            </div>
        <?php else: ?>
        <div class="bg-white shadow rounded-lg p-4">
        <div class="overflow-x-auto">
                <table class="min-w-full table-auto border text-sm text-left">
                <thead class="bg-blue-600 text-white uppercase text-xs">
<tr>
    <th class="py-3 px-4 text-left">#</th>
    <th class="py-3 px-4 text-left">Reported By</th>
    <th class="py-3 px-4 text-left">Concern</th>
    <th class="py-3 px-4 text-left">Date/Time</th>
    <th class="py-3 px-4 text-left">Status</th>
    <th class="py-3 px-4 text-left">Actions</th>
</tr>
</thead>
<tbody class="text-gray-700">
<?php $__currentLoopData = $concerns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $concern): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<tr class="<?php echo e($loop->odd ? 'bg-white' : 'bg-gray-100'); ?> border-t">
    <td class="py-3 px-4"><?php echo e($loop->iteration + ($concerns->firstItem() - 1)); ?></td>
    <td class="py-3 px-4">
        <?php if($concern->resident): ?>
            <?php echo e($concern->resident->first_name); ?>

            <?php echo e($concern->resident->middle_name ? $concern->resident->middle_name[0] . '.' : ''); ?>

            <?php echo e($concern->resident->last_name); ?>

            <?php echo e($concern->resident->suffix ? ', ' . $concern->resident->suffix : ''); ?>

        <?php else: ?>
            N/A
        <?php endif; ?>
    </td>
    <td class="py-3 px-4"><?php echo e($concern->title); ?></td>
    <td class="py-3 px-4">
    <?php if($concern->created_at): ?>
        <?php echo e(\Carbon\Carbon::parse($concern->created_at)
            ->timezone('Asia/Manila')
            ->format('F d, Y g:i A')); ?>

    <?php else: ?>
        <span class="text-gray-400 italic">N/A</span>
    <?php endif; ?>
</td>



                            <td class="py-3 px-4">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-medium
    <?php echo e($concern->status === 'resolved' ? 'bg-green-100 text-green-700' :
        ($concern->status === 'on going' ? 'bg-blue-100 text-blue-700' :
        'bg-yellow-100 text-yellow-700')); ?>">
    <?php echo e(ucfirst($concern->status ?? 'Pending')); ?>

</span>

                            </td>
                            <td class="py-3 px-4 flex gap-2 flex-wrap" id="actions-<?php echo e($concern->id); ?>">
    
    <button 
        class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-md transition"
        onclick="document.getElementById('modal-<?php echo e($concern->id); ?>').classList.remove('hidden')"
        title="View">
        <!-- Eye Icon -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>
    </button>

  
  <?php if($concern->status === 'pending'): ?>
<form id="status-form-<?php echo e($concern->id); ?>" method="POST" action="<?php echo e(route('captain.concerns.update-status', $concern->id)); ?>">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>
    <input type="hidden" name="status" value="on going">
  <button 
    type="button" 
    onclick='handleOnGoing(<?php echo e($concern->id); ?>, <?php echo json_encode($concern->title, 15, 512) ?>)'
    class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-md transition"
    title="Mark as In Progress">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M12 8v4l3 3m6 1a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
</button>
</form>
<?php endif; ?>



    
<?php if($concern->status === 'on going'): ?>
    <form id="solve-form-<?php echo e($concern->id); ?>" 
          action="<?php echo e(route('captain.concerns.status.update', $concern->id)); ?>" 
          method="POST" 
          class="status-form">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <input type="hidden" name="status" value="resolved">
        
        <button type="button"
            onclick='confirmSolve(<?php echo e($concern->id); ?>, <?php echo json_encode($concern->title, 15, 512) ?>)'
            class="bg-green-700 hover:bg-green-800 text-white p-2 rounded-md transition"
            title="Mark as Resolved">
            <!-- Check Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5 13l4 4L19 7" />
            </svg>
        </button>
    </form>
<?php endif; ?>


</td>

                        </tr>
                    

<div id="modal-<?php echo e($concern->id); ?>" 
     class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center hidden z-50 p-4">

    <div class="bg-white w-full max-w-2xl max-h-[90vh] overflow-y-auto p-8 rounded-2xl shadow-2xl relative border border-gray-100">

        <!-- Close Button -->
        <button onclick="closeModal(<?php echo e($concern->id); ?>)" 
                class="absolute top-4 right-5 text-gray-400 hover:text-red-500 text-2xl font-bold transition rounded-full"
                title="Close">
            &times;
        </button>

        <!-- Title -->
        <div class="flex items-center gap-2 mb-6 border-b pb-4">
            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-3-3v6m-9 3h18M4.5 6h15l1.5 12H3L4.5 6z" />
            </svg>
            <h2 class="text-2xl font-bold text-gray-800">Concern Details</h2>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm text-gray-700">

            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                <p class="font-medium text-gray-500">Reported By</p>
                <p class="text-gray-900 font-semibold"><?php echo e($concern->resident->full_name ?? 'Unknown'); ?></p>
            </div>

            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                <p class="font-medium text-gray-500">Zone</p>
                <p class="text-gray-900 font-semibold"><?php echo e($concern->zone ?? 'N/A'); ?></p>
            </div>

            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                <p class="font-medium text-gray-500">Street</p>
                <p class="text-gray-900 font-semibold"><?php echo e($concern->street ?? 'N/A'); ?></p>
            </div>

            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                <p class="font-medium text-gray-500">Date</p>
                <p class="text-gray-900 font-semibold"><?php echo e($concern->created_at->timezone('Asia/Manila')->format('F d, Y h:i A')); ?></p>
            </div>

            <div class="p-4 bg-gray-50 rounded-lg shadow-sm sm:col-span-2">
                <p class="font-medium text-gray-500">Concern</p>
                <p class="text-gray-900 font-semibold"><?php echo e($concern->title); ?></p>
            </div>

            <div class="p-4 bg-gray-50 rounded-lg shadow-sm sm:col-span-2">
                <p class="font-medium text-gray-500">Description</p>
                <p class="text-gray-900 mt-1 leading-relaxed"><?php echo e($concern->description); ?></p>
            </div>
        
<?php if($concern->image_path): ?>
<div class="sm:col-span-2">
    <p class="font-medium text-gray-500 mb-2">Concern Image</p>
    <div class="w-full h-40 overflow-hidden rounded-lg shadow-md cursor-pointer hover:opacity-90 transition"
         onclick="openImageFromModal(<?php echo e($concern->id); ?>, '<?php echo e(asset('storage/' . $concern->image_path)); ?>')">
        <img src="<?php echo e(asset('storage/' . $concern->image_path)); ?>" 
             alt="Concern Image" 
             class="w-full h-full object-contain">
    </div>
</div>
<?php endif; ?>




        </div>
    </div>
</div>

<!-- Fullscreen Image Overlay -->
<div id="fullscreen-img-overlay" class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50 hidden">
    <img id="fullscreen-img" class="max-h-screen max-w-screen object-contain rounded-lg shadow-2xl" src="" alt="Fullscreen">
    <button class="absolute top-6 right-6 text-white text-3xl font-bold hover:text-gray-300 transition" onclick="closeFullscreenImage()">&times;</button>
</div>


<script>

function closeModal(id) {
    const modal = document.getElementById(`modal-${id}`);
    modal.classList.add('hidden');
}

function openImageFullscreen(src) {
    const overlay = document.getElementById('fullscreen-img-overlay');
    const img = document.getElementById('fullscreen-img');
    img.src = src;
    overlay.classList.remove('hidden');
}

function closeFullscreenImage() {
    document.getElementById('fullscreen-img-overlay').classList.add('hidden');
}

// New function: open fullscreen and close modal
function openImageFromModal(id, imgSrc) {
    // Close the modal first
    closeModal(id);

    // Then open fullscreen image
    openImageFullscreen(imgSrc);
}


</script>


                        <!-- End Modal -->
                       

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <div class="mt-6">
                <?php echo e($concerns->appends(['status' => request('status')])->links()); ?>

</div>


            </div>
        <?php endif; ?>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function handleOnGoing(concernId, concernTitle) {
    Swal.fire({
        title: 'Are you sure?',
        html: `Mark the concern <strong>"${concernTitle}"</strong> as <b>On Going</b>?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#F59E0B',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, mark it!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`status-form-${concernId}`).submit();
        }
    });
}

function printLetter(id) {
    const section = document.getElementById(`print-section-${id}`);
    
    // Create a new window
    const printWindow = window.open('', '', 'width=900,height=1000');

    // Build a basic HTML structure
    printWindow.document.write(`
        <html>
        <head>
            <title>Invitation Letter</title>
            <style>
                body {
                    font-family: 'Times New Roman', serif;
                    padding: 60px;
                    color: black;
                }
                .text-center { text-align: center; }
                .text-right { text-align: right; }
                .font-bold { font-weight: bold; }
                .underline { text-decoration: underline; }
                p { margin: 10px 0; }
            </style>
        </head>
        <body></body>
        </html>
    `);

    printWindow.document.close();

    // Wait until the window loads fully before printing
    printWindow.onload = function () {
        const body = printWindow.document.body;

        // Clone the actual node so it keeps styles
        const clone = section.cloneNode(true);

        // Remove hidden or print:hidden classes if present
        clone.querySelectorAll('[class*="print:hidden"]').forEach(el => el.remove());

        body.appendChild(clone);

        // Add delay to ensure rendering before print
        setTimeout(() => {
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        }, 500);
    };
}


function hideOtherButtons(concernId) {
    // Wait for form submission and reload to complete (optional if you use AJAX)
    // If you want immediate UI feedback before reload, you can hide elements like this:
    const actionsContainer = document.getElementById(`actions-${concernId}`);
    const buttons = actionsContainer.querySelectorAll('button, form');
    
    buttons.forEach(el => {
        // Only keep the View button (identified by title or class)
        if (!el.title?.toLowerCase().includes('view')) {
            el.style.display = 'none';
        }
    });
}

function confirmSolve(concernId, concernTitle) {
    Swal.fire({
        title: 'Mark as Resolved?',
        html: `Are you sure you want to mark the concern <strong>"${concernTitle}"</strong> as <b>Resolved</b>?<br><small>This action cannot be undone.</small>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#38a169', // green
        cancelButtonColor: '#e53e3e',
        confirmButtonText: 'Yes, mark as resolved',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`solve-form-${concernId}`).submit();
        }
    });
}

</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/captain/concerns/index.blade.php ENDPATH**/ ?>