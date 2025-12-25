

<?php $__env->startSection('title', 'FAQ Management'); ?>

<?php $__env->startSection('content'); ?>
<div x-data="{ openAdd: false, openEdit: null, openView: null }" class="flex h-screen bg-gray-100 overflow-hidden">
    <?php echo $__env->make('partials.secretary-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1 flex flex-col overflow-hidden p-6">
        <?php echo $__env->make('partials.secretary-header', ['title' => 'FAQ Management'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php if(session('success')): ?>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: '<?php echo e(session('success')); ?>',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            });
        </script>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: '<?php echo e(session('error')); ?>',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            });
        </script>
    <?php endif; ?>
      <!-- Page Heading and Add Button -->
<!-- Page Heading and Add Button -->
<div class="mb-4 flex flex-col">
    <h1 class="text-2xl font-bold mb-2">FAQ Management</h1>
    <div class="flex justify-end">
        <button @click="openAdd = true" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Add FAQ
        </button>
    </div>
</div>


        <!-- FAQ Table -->
        <div class="bg-white shadow rounded-lg p-4">
            <div class="overflow-x-auto">
                <table class="w-full table-auto border text-sm">
                    <thead class="bg-blue-600 text-white">
                        <tr>
                            <th class="border px-4 py-2">Question</th>
                            <th class="border px-4 py-2">Category</th>
                            <th class="border px-4 py-2 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="<?php echo e($loop->odd ? 'bg-white' : 'bg-gray-100'); ?> border-t">
                            <td class="border px-4 py-2"><?php echo e($faq->question); ?></td>
                            <td class="border px-4 py-2"><?php echo e($faq->category ?? '-'); ?></td>
                            <td class="border px-4 py-2 text-center flex justify-center gap-2">
                                <!-- View Button -->
<button @click="openView = <?php echo e($faq->id); ?>"  class="bg-blue-600 hover:bg-blue-700 text-white w-9 h-9 flex items-center justify-center rounded-md shadow transition">
<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7
                     -1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        </svg>
</button>

<!-- Edit Button -->
<button @click="openEdit = <?php echo e($faq->id); ?>"  class="bg-yellow-100 hover:bg-yellow-200 text-yellow-600 w-9 h-9 flex items-center justify-center rounded-md shadow transition">
<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                </svg>
</button>

<!-- Delete Button -->
<!-- Delete Button -->
<button @click="deleteFaq(<?php echo e($faq->id); ?>, '<?php echo e(addslashes($faq->question)); ?>')" class="bg-red-600 text-white p-2 rounded hover:bg-red-700">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none"
         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0a1 1 0 011 1v0a1 1 0 01-1 1H7a1 1 0 01-1-1v0a1 1 0 011-1h10z"/>
    </svg>
</button>


                            </td>
                        </tr>

                        <!-- View Modal -->
                        <div x-show="openView === <?php echo e($faq->id); ?>" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" x-cloak>
                            <div class="bg-white rounded-lg shadow-lg w-1/3 p-6 relative">
                                <button @click="openView = null" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800">✕</button>
                                <h2 class="text-xl font-bold mb-4">View FAQ</h2>
                                <div class="mb-3">
                                    <label class="block mb-1 font-medium">Question</label>
                                    <p class="border px-3 py-2 rounded bg-gray-100"><?php echo e($faq->question); ?></p>
                                </div>
                                <div class="mb-3">
                                    <label class="block mb-1 font-medium">Answer</label>
                                    <p class="border px-3 py-2 rounded bg-gray-100"><?php echo e($faq->answer); ?></p>
                                </div>
                                <div class="mb-3">
                                    <label class="block mb-1 font-medium">Category</label>
                                    <p class="border px-3 py-2 rounded bg-gray-100"><?php echo e($faq->category ?? '-'); ?></p>
                                </div>
                                <div class="flex justify-end">
                                    <button @click="openView = null" class="px-4 py-2 border rounded hover:bg-gray-100">Close</button>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Modal -->
                        <div x-show="openEdit === <?php echo e($faq->id); ?>" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" x-cloak>
                            <div class="bg-white rounded-lg shadow-lg w-1/3 p-6 relative">
                                <button @click="openEdit = null" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800">✕</button>
                                <h2 class="text-xl font-bold mb-4">Edit FAQ</h2>
                                <form action="<?php echo e(route('secretary.faq.update', $faq->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>
                                    <div class="mb-3">
                                        <label class="block mb-1 font-medium">Question</label>
                                        <input type="text" name="question" value="<?php echo e($faq->question); ?>" class="w-full border px-3 py-2 rounded" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="block mb-1 font-medium">Answer</label>
                                        <textarea name="answer" class="w-full border px-3 py-2 rounded" rows="3" required><?php echo e($faq->answer); ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="block mb-1 font-medium">Category (optional)</label>
                                        <input type="text" name="category" value="<?php echo e($faq->category); ?>" class="w-full border px-3 py-2 rounded">
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="button" @click="openEdit = null" class="mr-2 px-4 py-2 border rounded hover:bg-gray-100">Cancel</button>
                                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td class="border px-4 py-2 text-center" colspan="3">No FAQs found.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <!-- Pagination links -->
<div class="mt-4">
    <?php echo e($faqs->links()); ?>

</div>
            </div>
        </div>

        <!-- Add FAQ Modal -->
        <div x-show="openAdd" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" x-cloak>
            <div class="bg-white rounded-lg shadow-lg w-1/3 p-6 relative">
                <button @click="openAdd = false" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800">✕</button>
                <h2 class="text-xl font-bold mb-4">Add New FAQ</h2>
                <form action="<?php echo e(route('secretary.faq.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label class="block mb-1 font-medium">Question</label>
                        <input type="text" name="question" class="w-full border px-3 py-2 rounded" required>
                    </div>
                    <div class="mb-3">
                        <label class="block mb-1 font-medium">Answer</label>
                        <textarea name="answer" class="w-full border px-3 py-2 rounded" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="block mb-1 font-medium">Category (optional)</label>
                        <input type="text" name="category" class="w-full border px-3 py-2 rounded">
                    </div>
                    <div class="flex justify-end">
                        <button type="button" @click="openAdd = false" class="mr-2 px-4 py-2 border rounded hover:bg-gray-100">Cancel</button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<script>

function deleteFaq(id, question) {
    Swal.fire({
        title: 'Are you sure?',
        html: `You are about to delete the FAQ: <strong>"${question}"</strong>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/secretary/faq/${id}`;
            
            const token = document.createElement('input');
            token.type = 'hidden';
            token.name = '_token';
            token.value = '<?php echo e(csrf_token()); ?>';
            form.appendChild(token);
            
            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';
            form.appendChild(method);
            
            document.body.appendChild(form);
            form.submit();
        }
    })
}


</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/faq/index.blade.php ENDPATH**/ ?>