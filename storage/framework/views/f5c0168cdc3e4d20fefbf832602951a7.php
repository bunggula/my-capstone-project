

<?php $__env->startSection('title', 'Barangay Information'); ?>

<?php $__env->startSection('content'); ?>
<div x-data="{ openModal: false }" class="flex h-screen bg-gray-100 text-gray-800">

    
    <?php echo $__env->make('partials.captain-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <div class="flex-1 flex flex-col overflow-hidden">

        
        <?php echo $__env->make('partials.captain-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        
        <?php if(session('success')): ?>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: '<?php echo e(session('success')); ?>', showConfirmButton: false, timer: 3000, timerProgressBar: true });
                });
            </script>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: '<?php echo e(session('error')); ?>', showConfirmButton: false, timer: 3000, timerProgressBar: true });
                });
            </script>
        <?php endif; ?>

        
        <div class="flex-1 p-4 md:p-6 overflow-y-auto">
            <div class="bg-white p-6 rounded-xl shadow-md w-full max-w-4xl mx-auto">

                <h1 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">Barangay Information</h1>

                
                <?php if($barangay->logo): ?>
                    <div class="mb-6 text-center">
                        <img src="<?php echo e(asset('storage/' . $barangay->logo)); ?>" alt="Barangay Logo"
                             class="h-20 w-20 mx-auto rounded-full shadow-md object-cover">
                    </div>
                <?php endif; ?>

                
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-gray-700 text-sm">
    <div class="bg-gray-50 p-2 rounded shadow-sm">
        <span class="font-semibold">Barangay Name:</span>
        <div><?php echo e($barangay->name); ?></div>
    </div>

    <div class="bg-gray-50 p-2 rounded shadow-sm">
        <span class="font-semibold">Chairperson:</span>
        <div><?php echo e($chairperson ? $chairperson->first_name . ' ' . $chairperson->last_name : 'Not assigned'); ?></div>
    </div>

    <div class="bg-gray-50 p-2 rounded shadow-sm">
        <span class="font-semibold">Secretary:</span>
        <div><?php echo e($secretary ? $secretary->first_name . ' ' . $secretary->last_name : 'Not assigned'); ?></div>
    </div>

    <div class="bg-gray-50 p-2 rounded shadow-sm">
        <span class="font-semibold">Contact Number:</span>
        <div><?php echo e($barangay->contact_number); ?></div>
    </div>

    <div class="bg-gray-50 p-2 rounded shadow-sm">
        <span class="font-semibold">Email:</span>
        <div><?php echo e($userEmail); ?></div>
    </div>

    <div class="bg-gray-50 p-2 rounded shadow-sm">
        <span class="font-semibold">Office Hours:</span>
        <div><?php echo e($barangay->office_hours); ?></div>
    </div>
</div>


<div class="mt-4 flex justify-end">
    <button @click="openModal = true"
            class="bg-blue-600 text-white px-4 py-1 rounded-lg hover:bg-blue-700 transition duration-300">
        Edit Information
    </button>
</div>



            </div>
        </div>
    </div>

    
    <div x-show="openModal" x-transition class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div @click.away="openModal = false"
             class="bg-white w-full max-w-3xl p-4 rounded-xl shadow-xl overflow-y-auto max-h-[80vh]">
            
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h2 class="text-lg font-semibold">Edit Barangay Information</h2>
                <button @click="openModal = false" class="text-gray-600 hover:text-gray-800 text-2xl">&times;</button>
            </div>

            <?php echo $__env->make('captain.barangay-info.edit', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/captain/barangay-info/show.blade.php ENDPATH**/ ?>