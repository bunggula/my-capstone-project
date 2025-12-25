

<?php $__env->startSection('title', 'Resident Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex h-screen overflow-hidden bg-gray-100">
    <?php echo $__env->make('partials.abc-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1 flex flex-col overflow-hidden">
    <?php echo $__env->make('partials.abc-header', ['title' => 'Resident Details'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="p-6">
        
        <a href="<?php echo e(url()->previous()); ?>" class="inline-block mb-4 text-sm text-blue-600 hover:underline">
            ← Back
        </a>

        <h2 class="text-3xl font-bold text-gray-800 mb-8">Resident Information</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700 text-sm">
            <!-- existing content continues here -->
                    <div>
                        <span class="font-medium text-gray-600">Name:</span><br>
                        <?php echo e($resident->first_name); ?> <?php echo e($resident->middle_name); ?> <?php echo e($resident->last_name); ?> <?php echo e($resident->suffix); ?>

                    </div>

                    <div>
                        <span class="font-medium text-gray-600">Gender:</span><br>
                        <?php echo e($resident->gender); ?>

                    </div>

                    <div>
                        <span class="font-medium text-gray-600">Birthdate:</span><br>
                        <?php echo e($resident->birthdate); ?>

                    </div>

                    <div>
                        <span class="font-medium text-gray-600">Age:</span><br>
                        <?php echo e($resident->age); ?>

                    </div>

                    <div>
                        <span class="font-medium text-gray-600">Civil Status:</span><br>
                        <?php echo e($resident->civil_status); ?>

                    </div>

                    <div>
                        <span class="font-medium text-gray-600">Category:</span><br>
                        <?php echo e($resident->category ?? 'N/A'); ?>

                    </div>

                    <div>
                        <span class="font-medium text-gray-600">Email:</span><br>
                        <?php echo e($resident->email ?? 'N/A'); ?>

                    </div>

                    <div>
                        <span class="font-medium text-gray-600">Phone:</span><br>
                        <?php echo e($resident->phone ?? 'N/A'); ?>

                    </div>

                    <div>
                        <span class="font-medium text-gray-600">Barangay:</span><br>
                        <?php echo e($resident->barangay->name ?? 'N/A'); ?>

                    </div>

                    <div class="md:col-span-2">
                        <span class="font-medium text-gray-600">Address:</span><br>
                        <?php echo e($resident->address); ?>

                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/abc/residents/show.blade.php ENDPATH**/ ?>