

<?php $__env->startSection('content'); ?>
<div class="flex min-h-screen font-sans">

    
    <?php echo $__env->make('partials.secretary-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <div class="flex-1 flex flex-col bg-gray-100">

        
        <?php echo $__env->make('partials.secretary-header', ['title' => '📄 Document Request Details'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        
        <?php if(in_array($request->status, ['approved', 'ready_for_pickup'])): ?>
            <a href="<?php echo e(route('secretary.document_requests.print', $request->id)); ?>" 
               target="_blank" 
               class="btn btn-success mt-3 ml-6 w-fit">
               🖨️ I-print ang Dokumento
            </a>
        <?php endif; ?>

        
        <div class="p-6 max-w-4xl mx-auto">
            <div class="bg-white shadow rounded-lg p-6 space-y-4">

                <h3 class="text-xl font-semibold text-blue-900 mb-4">Request Information</h3>

              

                
                <div class="text-sm space-y-2">
                    <p><strong class="text-gray-700">Resident:</strong> <?php echo e($request->resident->name ?? 'Unknown'); ?></p>
                    <p><strong class="text-gray-700">Title:</strong> <?php echo e($request->title); ?></p>
                    <p>
                        <strong class="text-gray-700">Status:</strong>
                        <span class="inline-block px-2 py-1 text-xs rounded-full font-medium
                            <?php echo e($request->status === 'approved' ? 'bg-green-100 text-green-800' :
                               ($request->status === 'rejected' ? 'bg-red-100 text-red-800' :
                               ($request->status === 'ready_for_pickup' ? 'bg-blue-100 text-blue-800' :
                               'bg-gray-100 text-gray-800'))); ?>">
                            <?php echo e(ucfirst($request->status ?? 'pending')); ?>

                        </span>
                    </p>
                    <p><strong class="text-gray-700">Submitted At:</strong> <?php echo e($request->created_at->format('F d, Y h:i A')); ?></p>
                </div>

                <hr class="my-4">

                <div>
                    <h4 class="text-lg font-medium text-blue-800 mb-2">Document Content</h4>
                    <div class="bg-gray-50 border rounded p-4 text-sm whitespace-pre-wrap leading-relaxed">
                        <?php echo e($request->content); ?>

                    </div>
                </div>

                <div class="pt-4">
                    <a href="<?php echo e(route('secretary.document_requests.index')); ?>"
                       class="inline-block px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-800 transition">
                        ⬅ Back to Requests
                    </a>
                    
                </div>
                
                <?php if(in_array($request->status, ['pending', 'approved'])): ?>
                    <div class="flex space-x-3 mb-6">
                        <?php if($request->status === 'pending'): ?>
                            
                            <form method="POST" action="<?php echo e(route('secretary.document_requests.update_status', [$request->id, 'approved'])); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                                    ✅ Approve
                                </button>
                            </form>

                            
                            <form method="POST" action="<?php echo e(route('secretary.document_requests.update_status', [$request->id, 'rejected'])); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">
                                    ❌ Reject
                                </button>
                            </form>
                        <?php elseif($request->status === 'approved'): ?>
                            
                            <form method="POST" action="<?php echo e(route('secretary.document_requests.update_status', [$request->id, 'ready_for_pickup'])); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                                    📦 Mark as Ready for Pickup
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/document_requests/show.blade.php ENDPATH**/ ?>