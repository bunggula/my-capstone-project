

<?php $__env->startSection('title', 'Municipality Services'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex h-screen overflow-hidden bg-gray-100">
    
    <?php echo $__env->make('partials.abc-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1 flex flex-col overflow-hidden">
        <?php echo $__env->make('partials.abc-header', ['title' => 'Municipality Residents'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="overflow-x-auto">
            <div class="flex-1 overflow-y-auto p-8">
                <div class="max-w-7xl mx-auto space-y-6">

                    
                    <h1 class="text-3xl font-bold text-gray-800 mb-4">Services</h1>

                    
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
                        <a href="<?php echo e(route('abc.services.index', ['status' => 'completed', 'barangay' => request('barangay')])); ?>"
                           class="block p-4 rounded shadow border-l-4 
                           <?php echo e(request('status') === 'completed' ? 'bg-blue-200 border-blue-600 text-blue-900' : 'bg-blue-100 border-blue-500 text-blue-700'); ?>">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-semibold text-sm">
                                        Completed
                                        <span class="text-xs text-gray-600">
                                            (<?php echo e(request('barangay') ? $barangays->firstWhere('id', request('barangay'))->name : 'All Barangays'); ?>)
                                        </span>
                                    </h4>
                                    <p class="text-2xl font-bold"><?php echo e($completedCount); ?></p>
                                </div>
                                <svg class="h-8 w-8 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6"/>
                                </svg>
                            </div>
                        </a>
                    </div>

                    
                    <form method="GET" action="<?php echo e(route('abc.services.index')); ?>" 
                          class="bg-white p-6 rounded-lg shadow flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                        
                        <div class="flex flex-wrap items-center gap-4">
                            
                            <label class="text-gray-700 font-semibold">Document Type:</label>
                            <select name="document_type" onchange="this.form.submit()" 
                                    class="border-gray-300 rounded px-3 py-2 shadow-sm">
                                <option value="">All</option>
                                <?php $__currentLoopData = $documentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($type); ?>" <?php echo e(request('document_type') == $type ? 'selected' : ''); ?>>
                                        <?php echo e($type); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>

                            
                            <label class="text-gray-700 font-semibold">Barangay:</label>
                            <select name="barangay" onchange="this.form.submit()" 
                                    class="border-gray-300 rounded px-3 py-2 shadow-sm">
                                <option value="">All</option>
                                <?php $__currentLoopData = $barangays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barangay): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($barangay->id); ?>" <?php echo e(request('barangay') == $barangay->id ? 'selected' : ''); ?>>
                                        <?php echo e($barangay->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        
                        <?php if(request()->hasAny(['document_type', 'barangay']) && (request('document_type') || request('barangay'))): ?>
                            <div class="flex items-center justify-end w-full lg:w-auto">
                                <a href="<?php echo e(route('abc.services.index')); ?>" 
                                   class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded shadow transition">
                                    Clear
                                </a>
                            </div>
                        <?php endif; ?>
                    </form>

                    
                    <?php if($requests->count()): ?>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mt-6">
                            <?php
                                // Group requests by document type
                                $grouped = $requests->groupBy('document_type');
                            ?>

                            <?php $__currentLoopData = $grouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $docType => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="bg-green-100 border-l-4 border-green-500 p-4 rounded shadow hover:shadow-lg transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-semibold text-sm capitalize"><?php echo e($docType ?? 'N/A'); ?></h4>
                                            <p class="text-2xl font-bold"><?php echo e($group->count()); ?></p>
                                            <p class="text-xs text-gray-600">
                                                <?php if(request('barangay')): ?>
                                                    <?php echo e($barangays->firstWhere('id', request('barangay'))->name); ?>

                                                <?php else: ?>
                                                    All Barangays
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                        <svg class="h-8 w-8 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6"/>
                                        </svg>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center text-gray-600 py-6">No document requests found.</div>
                    <?php endif; ?>

                    
                    <div class="mt-6">
                        <?php echo e($requests->appends(request()->query())->links()); ?>

                    </div>

                </div>
            </div>
        </div>
    </main>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/abc/services/index.blade.php ENDPATH**/ ?>