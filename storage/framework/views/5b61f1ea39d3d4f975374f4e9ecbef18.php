

<?php $__env->startSection('content'); ?>
<div x-data="{ editingProposal: null }" class="flex h-screen font-sans overflow-hidden bg-gray-100">
    <?php echo $__env->make('partials.captain-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1 flex flex-col max-h-screen overflow-y-auto">
        <?php echo $__env->make('partials.captain-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="p-6 max-w-5xl mx-auto bg-white mt-6 rounded shadow min-h-[60vh]">
            <h1 class="text-2xl font-bold mb-4">Rejected Proposals</h1>

            <a href="<?php echo e(route('captain.proposals.index')); ?>" class="text-blue-600 underline text-sm mb-4 inline-block">← Back to My Proposals</a>

            <?php if($proposals->isEmpty()): ?>
                <p class="text-gray-600 text-center">No rejected proposals yet.</p>
            <?php else: ?>
                <?php $__currentLoopData = $proposals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proposal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div x-data="{ open: false }" class="border rounded mb-4 bg-gray-50 shadow-sm transition-all">
                    <!-- Header -->
                    <div @click="open = !open" class="cursor-pointer px-4 py-3 bg-red-100 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-red-800"><?php echo e($proposal->title); ?></h2>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-white px-2 py-1 rounded bg-red-600">Rejected</span>
                            <svg :class="{'rotate-180': open}" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>

                    <!-- Dropdown Content -->
                    <div class="overflow-hidden transition-all duration-500 ease-in-out" :style="open ? 'max-height: 1000px;' : 'max-height: 0;'">
                        <div class="px-4 py-4 border-t">
                            <?php if($proposal->image): ?>
                                <img src="<?php echo e(asset('storage/' . $proposal->image)); ?>" alt="Proposal Image" class="w-full max-h-64 object-cover rounded mb-4">
                            <?php endif; ?>

                            <p class="mb-2"><strong>Barangay:</strong> <?php echo e($proposal->barangayInfo->name ?? 'N/A'); ?></p>
                            <p class="mb-2"><strong>Created At:</strong> <?php echo e($proposal->created_at->format('F d, Y')); ?></p>

                            <?php if($proposal->description): ?>
                                <div class="mt-2">
                                    <h3 class="font-semibold">Rationale:</h3>
                                    <p class="text-gray-700 whitespace-pre-line"><?php echo e($proposal->description); ?></p>
                                </div>
                            <?php endif; ?>

                            <?php if($proposal->file): ?>
                                <div class="mt-3">
                                    <a href="<?php echo e(asset('storage/' . $proposal->file)); ?>" target="_blank" class="text-blue-600 underline">
                                        📎 View Attached Document
                                    </a>
                                </div>
                            <?php endif; ?>

                            <?php if($proposal->rejection_reason): ?>
                                <div class="mt-4 bg-red-50 border border-red-300 text-red-800 p-3 rounded">
                                    <strong>Rejection Reason:</strong>
                                    <p class="mt-1 whitespace-pre-line"><?php echo e($proposal->rejection_reason); ?></p>
                                </div>
                            <?php endif; ?>

                            <!-- Resubmit Button -->
                            <div class="mt-4">
                                <button @click.stop="editingProposal = <?php echo e($proposal->toJson()); ?>" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                                    🔄 Resubmit Proposal
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>

        <!-- Resubmit Modal -->
        <div x-show="editingProposal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
            <div class="bg-white rounded-lg shadow-lg w-full max-w-xl p-6 relative" @click.outside="editingProposal = null">
                <h2 class="text-xl font-bold mb-4">Resubmit Proposal</h2>

                <form :action="'/captain/proposals/' + editingProposal.id" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Title</label>
                        <input type="text" name="title" x-model="editingProposal.title" class="w-full border rounded px-3 py-2 mt-1" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Description</label>
                        <textarea name="description" x-model="editingProposal.description" rows="4" class="w-full border rounded px-3 py-2 mt-1"></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Update Image</label>
                        <input type="file" name="image" accept="image/*" class="mt-1">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Update Document</label>
                        <input type="file" name="file" accept=".pdf,.doc,.docx" class="mt-1">
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="editingProposal = null" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Submit Again</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<!-- SweetAlert for Success -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if(session('success')): ?>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '<?php echo e(session('success')); ?>',
            timer: 2000,
            showConfirmButton: false
        });
    });
</script>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/captain/proposals/rejected.blade.php ENDPATH**/ ?>