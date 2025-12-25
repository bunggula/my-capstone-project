

<?php $__env->startSection('content'); ?>
<div x-data="{ editingProposal: null, showCreateModal: false }" class="flex h-screen font-sans overflow-hidden bg-gray-100">
    <?php echo $__env->make('partials.captain-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1 flex flex-col max-h-screen overflow-y-auto">
        <?php echo $__env->make('partials.captain-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="p-6 max-w-7xl mx-auto bg-white mt-6 rounded shadow min-h-[60vh] flex flex-col">
            <h1 class="text-2xl font-bold mb-6">All Proposals</h1>

            <button 
    @click="showCreateModal = true" 
    type="button" 
    class="bg-blue-600 text-white px-4 py-2 rounded mb-4"
>
    + Create Proposal
</button>


            <?php if($proposals->isEmpty()): ?>
                <div class="flex-1 flex items-center justify-center">
                    <p class="text-gray-600 text-lg text-center">No proposals available.</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto bg-white border border-gray-200">
                        <thead>
                            <tr class="bg-gray-100 text-left text-sm font-medium text-gray-700">
                                <th class="py-3 px-4 border-b">#</th>
                                <th class="py-3 px-4 border-b">Title</th>
                                <th class="py-3 px-4 border-b">Barangay</th>
                                <th class="py-3 px-4 border-b">Status</th>
                                <th class="py-3 px-4 border-b">Created</th>
                                <th class="py-3 px-4 border-b">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-800">
                            <?php $__currentLoopData = $proposals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $proposal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="<?php echo e($loop->odd ? 'bg-gray-50' : 'bg-white'); ?> border-b">
                                    <td class="py-3 px-4"><?php echo e($index + 1); ?></td>
                                    <td class="py-3 px-4"><?php echo e($proposal->title); ?></td>
                                    <td class="py-3 px-4"><?php echo e($proposal->barangayInfo->name ?? 'N/A'); ?></td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-1 text-xs text-white rounded bg-<?php echo e($proposal->status === 'pending' ? 'yellow-500' : (
                                                $proposal->status === 'rejected' ? 'red-500' : (
                                                    $proposal->status === 'approved' ? 'green-600' : 'gray-500'
                                                )
                                        )); ?>">
                                            <?php echo e(ucfirst($proposal->status)); ?>

                                        </span>
                                    </td>
                                    <td class="py-3 px-4"><?php echo e($proposal->created_at->format('F d, Y')); ?></td>
                                    <td class="py-3 px-4 space-x-2">
                                        <?php if($proposal->status === 'rejected' && $proposal->rejection_reason): ?>
                                            <button 
                                                @click="Swal.fire('Rejection Reason', '<?php echo e($proposal->rejection_reason); ?>', 'info')" 
                                                class="text-xs text-red-600 underline"
                                            >
                                                View Reason
                                            </button>
                                        <?php endif; ?>

                                        <?php if($proposal->status === 'pending' || $proposal->status === 'rejected'): ?>
                                            <button 
                                                @click="editingProposal = <?php echo e($proposal->toJson()); ?>" 
                                                class="bg-yellow-500 text-white text-xs px-2 py-1 rounded hover:bg-yellow-600"
                                            >
                                                <?php echo e($proposal->status === 'rejected' ? 'Resubmit' : 'Edit'); ?>

                                            </button>
                                        <?php endif; ?>

                                        <?php if($proposal->status !== 'approved'): ?>
                                            <form 
                                                action="<?php echo e(route('captain.proposals.destroy', $proposal->id)); ?>" 
                                                method="POST" 
                                                x-ref="deleteForm<?php echo e($proposal->id); ?>"
                                                @submit.prevent="Swal.fire({
                                                    title: 'Are you sure?',
                                                    text: 'This cannot be undone.',
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#e3342f',
                                                    cancelButtonColor: '#6b7280',
                                                    confirmButtonText: 'Yes, delete it!'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        $refs.deleteForm<?php echo e($proposal->id); ?>.submit()
                                                    }
                                                })"
                                            >
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="bg-red-600 text-white text-xs px-2 py-1 rounded hover:bg-red-700">
                                                    Delete
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Create Modal -->
       
<div x-show="showCreateModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6 relative" @click.outside="showCreateModal = false">
        <h2 class="text-xl font-bold mb-4">Create Proposal</h2>
        <?php echo $__env->make('captain.proposals.create', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?> <!-- should contain just the <form> -->
    </div>
</div>


        <!-- Edit Modal -->
        <div x-show="editingProposal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-xl p-6 relative" @click.outside="editingProposal = null">
                <h2 class="text-xl font-bold mb-4">Edit Proposal</h2>

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

                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="editingProposal = null" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/captain/proposals/index.blade.php ENDPATH**/ ?>