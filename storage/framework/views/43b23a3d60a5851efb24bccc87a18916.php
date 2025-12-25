

<?php $__env->startSection('content'); ?>
<div class="flex min-h-screen bg-gray-100 font-sans">
    
    <?php echo $__env->make('partials.abc-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <main class="flex-1 p-6">
        <div class="max-w-4xl mx-auto bg-white p-10 rounded-xl shadow-sm border border-gray-200">

            
            <div class="mb-6">
                <a href="<?php echo e(route('abc.proposals.index')); ?>" class="inline-flex items-center text-sm text-blue-600 hover:underline">
                    ← Back to Proposals
                </a>
            </div>

            
            <div class="text-center mb-8">
                <h1 class="text-xl font-bold uppercase">Association of Barangay Captains</h1>
                <p class="text-sm text-gray-600">Municipality of [Your Municipality]</p>
                <p class="text-sm text-gray-600">[Your Province]</p>
                <hr class="my-4 border-gray-300">
            </div>

            
            <div class="text-gray-800 leading-relaxed space-y-5">
                <p class="text-sm"><?php echo e(\Carbon\Carbon::now()->format('F d, Y')); ?></p>

                <p>To whom it may concern,</p>

                <p>
                    I am writing on behalf of Barangay <strong><?php echo e($proposal->barangay); ?></strong> to formally submit the proposal entitled:
                </p>

                <p class="text-lg font-semibold text-blue-900">"<?php echo e($proposal->title); ?>"</p>

                <p>
                    <?php echo e($proposal->description); ?>

                </p>

                <?php if($proposal->file): ?>
                <p>
                    Attached herein is the relevant document for your review and consideration. You may view the file using the link below.
                </p>
                <?php endif; ?>

                <p>
                    Thank you for your kind attention to this matter.
                </p>

                <p>Sincerely,</p>

                
                <div class="mt-6">
                    <p class="font-semibold">
                        <?php echo e($proposal->captain_name ?? 'Hon. [Barangay Captain Name]'); ?>

                    </p>
                    <p>Barangay Captain</p>
                    <p>Barangay <?php echo e($proposal->barangay); ?></p>
                </div>
            </div>

            
            <?php if($proposal->file): ?>
            <div class="mt-6">
                <h3 class="text-sm text-gray-600 uppercase tracking-wide mb-2">Attachment</h3>
                <a href="<?php echo e(asset('storage/' . $proposal->file)); ?>" target="_blank" class="inline-flex items-center text-blue-600 hover:underline">
                    📎 <span class="ml-1">View Attached File</span>
                </a>
            </div>
            <?php endif; ?>

            
            <div class="pt-6 mt-6 border-t border-gray-200 flex gap-4">
                <form action="<?php echo e(route('abc.proposals.approve', $proposal->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md shadow transition">
                        ✅ Approve
                    </button>
                </form>

                <form action="<?php echo e(route('abc.proposals.reject', $proposal->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-md shadow transition">
                        ❌ Reject
                    </button>
                </form>
            </div>
        </div>
    </main>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/abc/proposals/show.blade.php ENDPATH**/ ?>