

<?php $__env->startSection('title', 'Municipality Residents'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex h-screen overflow-hidden bg-gray-100">

    
    <?php echo $__env->make('partials.abc-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <main class="flex-1 flex flex-col overflow-hidden">
        <?php echo $__env->make('partials.abc-header', ['title' => 'Municipality Residents'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="overflow-x-auto">
            <div class="flex-1 overflow-y-auto p-8">
                <div class="max-w-7xl mx-auto space-y-6">

                    <h1 class="text-3xl font-bold text-gray-800 mb-4">Residents Summary</h1>

                    
                    <form method="GET" class="mb-6">
                        <div class="w-64">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Select Barangay:</label>
                            <select name="barangay" onchange="this.form.submit()"
                                class="w-full border px-3 py-2 rounded shadow-sm text-sm bg-white">

                                <option value="">All Barangays</option>

                                <?php $__currentLoopData = $barangays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barangay): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($barangay->id); ?>"
                                        <?php echo e(request('barangay') == $barangay->id ? 'selected' : ''); ?>>
                                        <?php echo e($barangay->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </form>

                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">

                        
                        <div class="flex items-center justify-between p-8 rounded-2xl shadow-lg border-l-8 bg-green-200 border-green-700 text-green-800">
                            <div>
                                <h4 class="font-bold text-xl">Residents</h4>
                                <p class="text-5xl font-extrabold mt-3"><?php echo e($stats['all']); ?></p>
                            </div>
                            <i data-lucide="users" class="w-16 h-16 opacity-80 text-green-700"></i>
                        </div>

                        
                        <div class="flex items-center justify-between p-8 rounded-2xl shadow-lg border-l-8 bg-blue-200 border-blue-700 text-blue-800">
                            <div>
                                <h4 class="font-bold text-xl">Male</h4>
                                <p class="text-5xl font-extrabold mt-3"><?php echo e($stats['male']); ?></p>
                            </div>
                            <i data-lucide="mars" class="w-16 h-16 opacity-80 text-blue-700"></i>
                        </div>

                        
                        <div class="flex items-center justify-between p-8 rounded-2xl shadow-lg border-l-8 bg-pink-200 border-pink-700 text-pink-800">
                            <div>
                                <h4 class="font-bold text-xl">Female</h4>
                                <p class="text-5xl font-extrabold mt-3"><?php echo e($stats['female']); ?></p>
                            </div>
                            <i data-lucide="venus" class="w-16 h-16 opacity-80 text-pink-700"></i>
                        </div>

                        
                        <div class="flex items-center justify-between p-8 rounded-2xl shadow-lg border-l-8 bg-yellow-200 border-yellow-700 text-yellow-800">
                            <div>
                                <h4 class="font-bold text-xl">Minors (Below 18)</h4>
                                <p class="text-5xl font-extrabold mt-3"><?php echo e($stats['minors']); ?></p>
                            </div>
                            <i data-lucide="baby" class="w-16 h-16 opacity-80 text-yellow-700"></i>
                        </div>

                    </div>

                    <script>
                        lucide.createIcons();
                    </script>

               

                </div>
            </div>
        </div>

    </main>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/abc/residents/index.blade.php ENDPATH**/ ?>