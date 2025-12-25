

<?php $__env->startSection('title', 'Pending Resident Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex h-screen overflow-hidden font-sans">

    <!-- Sidebar -->
    <?php echo $__env->make('partials.secretary-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Main content -->
    <div class="flex flex-col flex-1 max-h-screen">

        <!-- Header -->
        <?php echo $__env->make('partials.secretary-header', ['title' => 'Pending Resident Details'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Scrollable content -->
        <main class="flex-1 overflow-y-auto p-8 bg-gray-50">
            
            <a href="<?php echo e(route('secretary.residents.pending', $resident->id)); ?>" 
                class="text-blue-600 hover:underline mb-6 inline-flex items-center">
                ← Back to Pending Residents List
            </a>

            
            <h2 class="text-3xl font-bold text-center mb-8 border-b pb-3">Pending Resident Details</h2>

            
            <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-md p-8 space-y-6 text-gray-800">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-4 text-sm">

                    <div>
                        <span class="font-semibold text-gray-700">First Name:</span>
                        <p class="mt-1"><?php echo e($resident->first_name); ?></p>
                    </div>

                    <div>
                        <span class="font-semibold text-gray-700">Middle Name:</span>
                        <p class="mt-1"><?php echo e($resident->middle_name ?? 'N/A'); ?></p>
                    </div>

                    <div>
                        <span class="font-semibold text-gray-700">Last Name:</span>
                        <p class="mt-1"><?php echo e($resident->last_name); ?></p>
                    </div>

                    <div>
                        <span class="font-semibold text-gray-700">Suffix:</span>
                        <p class="mt-1"><?php echo e($resident->suffix ?? 'N/A'); ?></p>
                    </div>

                    <div>
                        <span class="font-semibold text-gray-700">Gender:</span>
                        <p class="mt-1"><?php echo e($resident->gender); ?></p>
                    </div>

                    <div>
                        <span class="font-semibold text-gray-700">Birthdate:</span>
                        <p class="mt-1"><?php echo e(\Carbon\Carbon::parse($resident->birthdate)->format('F d, Y')); ?></p>
                    </div>

                    <div>
                        <span class="font-semibold text-gray-700">Age:</span>
                        <p class="mt-1"><?php echo e($resident->age); ?></p>
                    </div>

                    <div>
                        <span class="font-semibold text-gray-700">Civil Status:</span>
                        <p class="mt-1"><?php echo e($resident->civil_status); ?></p>
                    </div>

                    <div>
                        <span class="font-semibold text-gray-700">Category:</span>
                        <p class="mt-1"><?php echo e($resident->category ?? 'N/A'); ?></p>
                    </div>

                    <div>
                        <span class="font-semibold text-gray-700">Zone:</span>
                        <p class="mt-1"><?php echo e($resident->zone); ?></p>
                    </div>

                    <div>
                        <span class="font-semibold text-gray-700">Email:</span>
                        <p class="mt-1"><?php echo e($resident->email ?? 'N/A'); ?></p>
                    </div>

                    <div>
                        <span class="font-semibold text-gray-700">Phone:</span>
                        <p class="mt-1"><?php echo e($resident->phone ?? 'N/A'); ?></p>
                    </div>

                    <div>
                        <span class="font-semibold text-gray-700">Barangay:</span>
                        <p class="mt-1"><?php echo e($resident->barangay->name ?? 'N/A'); ?></p>
                    </div>

                    <div class="md:col-span-2">
                        <span class="font-semibold text-gray-700">Address:</span>
                        <p class="mt-1"><?php echo e($resident->address); ?></p>
                    </div>

                    <div class="md:col-span-2">
                        <span class="font-semibold text-gray-700">Status:</span>
                        <p class="mt-1">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                <?php echo e($resident->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-600'); ?>">
                                <?php echo e(ucfirst($resident->status)); ?>

                            </span>
                        </p>
                    </div>

                    
                    <div class="md:col-span-2">
    <span class="font-semibold text-gray-700">Proof of Residency:</span>
    <?php if($resident->proof_of_residency): ?>
        <img src="<?php echo e(asset('storage/' . $resident->proof_of_residency)); ?>" alt="Proof Image" class="mt-2 max-w-md rounded shadow">
    <?php else: ?>
        <p class="mt-1 text-gray-500 italic">No proof uploaded.</p>
    <?php endif; ?>
</div>

                    </div>
                    
                </div>
            </div>
        </main>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/residents/pending-show.blade.php ENDPATH**/ ?>