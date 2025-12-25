

<?php $__env->startSection('title', 'Resident Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex h-screen overflow-hidden font-sans bg-gray-50">

    <!-- Sidebar -->
    <?php echo $__env->make('partials.secretary-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Main content area -->
    <div class="flex flex-col flex-1 max-h-screen">

        <!-- Header -->
        <?php echo $__env->make('partials.secretary-header', ['title' => 'Resident Details'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Scrollable content -->
        <main class="flex-1 overflow-y-auto p-8">

            
            <a href="<?php echo e(route('secretary.residents.index')); ?>"
                class="inline-flex items-center text-blue-600 hover:underline mb-6 font-semibold transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 19l-7-7 7-7" />
                </svg>
                Back to Residents List
            </a>

            
            <h2 class="text-3xl font-extrabold text-center mb-10 border-b border-gray-300 pb-3 text-gray-800 tracking-wide">
                Resident Details
            </h2>

            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-6 text-gray-700 text-sm leading-relaxed">

                <div class="py-2 rounded-md px-4 bg-white shadow-sm hover:shadow-md transition-shadow duration-300">
                    <span class="font-semibold text-gray-900">First Name:</span> <?php echo e($resident->first_name); ?>

                </div>
                <div class="py-2 rounded-md px-4 bg-white shadow-sm hover:shadow-md transition-shadow duration-300">
                    <span class="font-semibold text-gray-900">Middle Name:</span> <?php echo e($resident->middle_name); ?>

                </div>
                <div class="py-2 rounded-md px-4 bg-white shadow-sm hover:shadow-md transition-shadow duration-300">
                    <span class="font-semibold text-gray-900">Last Name:</span> <?php echo e($resident->last_name); ?>

                </div>
                <div class="py-2 rounded-md px-4 bg-white shadow-sm hover:shadow-md transition-shadow duration-300">
                    <span class="font-semibold text-gray-900">Suffix:</span> <?php echo e($resident->suffix ?? 'N/A'); ?>

                </div>
                <div class="py-2 rounded-md px-4 bg-white shadow-sm hover:shadow-md transition-shadow duration-300">
                    <span class="font-semibold text-gray-900">Sex:</span> <?php echo e($resident->gender); ?>

                </div>
                <div class="py-2 rounded-md px-4 bg-white shadow-sm hover:shadow-md transition-shadow duration-300">
                    <span class="font-semibold text-gray-900">Birthdate:</span> <?php echo e(\Carbon\Carbon::parse($resident->birthdate)->format('F d, Y')); ?>

                </div>
                <div class="py-2 rounded-md px-4 bg-white shadow-sm hover:shadow-md transition-shadow duration-300">
                    <span class="font-semibold text-gray-900">Age:</span> <?php echo e($resident->age); ?>

                </div>
                <div class="py-2 rounded-md px-4 bg-white shadow-sm hover:shadow-md transition-shadow duration-300">
                    <span class="font-semibold text-gray-900">Civil Status:</span> <?php echo e($resident->civil_status); ?>

                </div>
                <div class="py-2 rounded-md px-4 bg-white shadow-sm hover:shadow-md transition-shadow duration-300">
                    <span class="font-semibold text-gray-900">Category:</span> <?php echo e($resident->category ?? 'N/A'); ?>

                </div>
                <div class="py-2 rounded-md px-4 bg-white shadow-sm hover:shadow-md transition-shadow duration-300">
                    <span class="font-semibold text-gray-900">Email:</span> <?php echo e($resident->email ?? 'N/A'); ?>

                </div>
                <div class="py-2 rounded-md px-4 bg-white shadow-sm hover:shadow-md transition-shadow duration-300">
                    <span class="font-semibold text-gray-900">Phone:</span> <?php echo e($resident->phone ?? 'N/A'); ?>

                </div>
                <div class="py-2 rounded-md px-4 bg-white shadow-sm hover:shadow-md transition-shadow duration-300">
                    <span class="font-semibold text-gray-900">Barangay:</span> <?php echo e($resident->barangay->name ?? 'N/A'); ?>

                </div>
                <div class="py-2 md:col-span-2 rounded-md px-6 bg-white shadow-sm hover:shadow-md transition-shadow duration-300">
                    <span class="font-semibold text-gray-900">Address:</span> <?php echo e($resident->address); ?>

                </div>
            </div>
        </main>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/residents/show.blade.php ENDPATH**/ ?>