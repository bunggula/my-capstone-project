
<?php $__env->startSection('title', 'Municipality Concerns'); ?>
<?php $__env->startSection('content'); ?>
<div class="flex h-screen overflow-hidden bg-gray-100">
    <?php echo $__env->make('partials.abc-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Main content -->
    <main class="flex-1 flex flex-col overflow-hidden">
        <?php echo $__env->make('partials.abc-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="overflow-x-auto">
            <div class="flex-1 overflow-y-auto p-8">
                <div class="max-w-7xl mx-auto space-y-6">

                    
                    <h1 class="text-2xl font-bold text-blue-900 mb-6">Resolved Concerns</h1>

                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6 w-full">
                        <a href="<?php echo e(route('abc.concerns.index', array_merge(request()->query(), ['status' => 'resolved']))); ?>"
                           class="flex items-center justify-between p-4 rounded shadow border-l-4 transition
                           <?php echo e(request('status') === 'resolved' 
                                 ? 'bg-green-200 border-green-700 text-green-800' 
                                 : 'bg-green-100 border-green-600 text-green-700 hover:bg-green-200'); ?>">
                            <div>
                                <h4 class="font-semibold">
                                    Resolved
                                    <span class="text-xs text-gray-600">
                                        (<?php echo e(request('barangay') 
                                            ? $barangays->firstWhere('id', request('barangay'))->name 
                                            : 'All Barangays'); ?>)
                                    </span>
                                </h4>
                                <p class="text-2xl font-bold"><?php echo e($filteredCount); ?></p>
                            </div>
                            <i data-lucide="check-circle" class="w-10 h-10 opacity-70 text-green-600"></i>
                        </a>
                    </div>

                    
                    <div class="flex items-center gap-4 mb-6" x-data>
                        <form method="GET" action="<?php echo e(route('abc.concerns.index')); ?>" x-ref="filterForm" class="flex flex-wrap items-center gap-2">
                            
                            <select name="barangay" x-model="selectedBarangay" @change="$refs.filterForm.submit()"
                                    class="border border-gray-300 rounded px-3 py-2 text-sm focus:ring focus:border-blue-500">
                                <option value="">-- Select a Barangay --</option>
                                <?php $__currentLoopData = $barangays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barangay): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($barangay->id); ?>" <?php echo e(request('barangay') == $barangay->id ? 'selected' : ''); ?>>
                                        <?php echo e($barangay->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>

                            
                            <select name="month" @change="$refs.filterForm.submit()"
                                    class="border border-gray-300 rounded px-3 py-2 text-sm focus:ring focus:border-blue-500">
                                <option value="">Select months</option>
                                <?php $__currentLoopData = range(1, 12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($m); ?>" <?php echo e(request('month') == $m ? 'selected' : ''); ?>>
                                        <?php echo e(\Carbon\Carbon::create()->month($m)->format('F')); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>

                            
                            <select name="year" @change="$refs.filterForm.submit()"
                                    class="border border-gray-300 rounded px-3 py-2 text-sm focus:ring focus:border-blue-500">
                                <option value="">Select Years</option>
                                <?php $__currentLoopData = range(date('Y'), date('Y') - 5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($y); ?>" <?php echo e(request('year') == $y ? 'selected' : ''); ?>>
                                        <?php echo e($y); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>

                            
                            <?php if(request('barangay') || request('month') || request('year')): ?>
                                <a href="<?php echo e(route('abc.concerns.index')); ?>" 
                                   class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded shadow transition">
                                   Clear
                                </a>
                            <?php endif; ?>
                        </form>
                    </div>

                    
                    <?php if($concerns->count()): ?>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-6">
                            <?php
                                // Group by concern type/title
                                $grouped = $concerns->groupBy('title');
                            ?>

                            <?php $__currentLoopData = $grouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $title => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="bg-yellow-100 border-l-4 border-yellow-500 p-4 rounded shadow hover:shadow-lg transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-semibold text-sm capitalize"><?php echo e($title); ?></h4>
                                            <p class="text-2xl font-bold"><?php echo e($group->count()); ?></p>
                                            <p class="text-xs text-gray-600">
                                                <?php if(request('barangay')): ?>
                                                    <?php echo e($barangays->firstWhere('id', request('barangay'))->name); ?>

                                                <?php else: ?>
                                                    All Barangays
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                        <i data-lucide="alert-circle" class="h-8 w-8 opacity-50 text-yellow-600"></i>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500 mt-6 text-center">No resolved concerns available.</p>
                    <?php endif; ?>

                    
                    <div class="mt-6">
                        <?php echo e($concerns->appends(request()->query())->links()); ?>

                    </div>

                </div>
            </div>
        </div>
    </main>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/abc/concerns/index.blade.php ENDPATH**/ ?>