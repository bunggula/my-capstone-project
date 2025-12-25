

<?php $__env->startSection('title', 'Barangay Events'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex w-screen h-screen font-sans overflow-hidden">

    
    <?php echo $__env->make('partials.captain-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <main class="flex-1 flex flex-col relative max-h-screen overflow-hidden">

        
        <?php echo $__env->make('partials.captain-header', ['title' => 'Barangay Events'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        
        <div class="flex-1 overflow-y-auto p-8 bg-gray-50">
            <div class="container mx-auto p-6 bg-white rounded shadow">

                
                <div class="mb-6">
                    <a href="<?php echo e(route('captain.dashboard')); ?>" 
                       class="inline-flex items-center bg-yellow-300 text-blue-900 px-4 py-2 rounded shadow hover:bg-yellow-400 transition font-semibold">
                        ← Back to Dashboard
                    </a>
                </div>

                
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">
                        All Events of Barangay <?php echo e(Auth::user()->barangay->name ?? 'N/A'); ?>

                    </h1>
                </div>

                
                <?php if(session('success')): ?>
                    <div id="success-message" class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded shadow">
                        ✅ <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-sm text-sm">
                        <thead>
                            <tr class="bg-gray-100 text-left">
                                <th class="px-4 py-3 border-b">Title</th>
                                <th class="px-4 py-3 border-b">Date</th>
                                <th class="px-4 py-3 border-b">Time</th>
                                <th class="px-4 py-3 border-b">Status</th>
                                <th class="px-4 py-3 border-b text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php if($event->barangay_id === Auth::user()->barangay_id): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 border-b"><?php echo e($event->title); ?></td>
                                        <td class="px-4 py-3 border-b"><?php echo e(\Carbon\Carbon::parse($event->date)->format('F d, Y')); ?></td>
                                        <td class="px-4 py-3 border-b"><?php echo e(\Carbon\Carbon::parse($event->time)->format('h:i A')); ?></td>
                                        <td class="px-4 py-3 border-b">
                                            <span class="px-2 py-1 rounded text-xs font-semibold 
                                                <?php echo e($event->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                                    ($event->status === 'rejected' ? 'bg-red-100 text-red-800' : 
                                                    'bg-yellow-100 text-yellow-800')); ?>">
                                                <?php echo e(ucfirst($event->status)); ?>

                                            </span>
                                        </td>
                                        <td class="px-4 py-3 border-b text-center">
                                            <a href="<?php echo e(route('captain.events.index', $event->id)); ?>"
                                               class="bg-blue-600 text-white px-3 py-1.5 rounded hover:bg-blue-700 transition">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-gray-500 py-6">
                                        No events found for your barangay.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                
                <div class="mt-6">
                    <?php echo e($events->links()); ?>

                </div>

            </div>
        </div>
    </main>
</div>


<script>
    setTimeout(() => {
        const msg = document.getElementById('success-message');
        if (msg) msg.style.opacity = '0';
    }, 3000);
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/captain/events/pending.blade.php ENDPATH**/ ?>