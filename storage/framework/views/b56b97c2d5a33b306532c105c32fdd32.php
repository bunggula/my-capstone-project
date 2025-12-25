

<?php $__env->startSection('content'); ?>
<div class="p-6 max-w-5xl mx-auto">
    <h2 class="text-xl font-bold mb-4">Add New BRCO Report</h2>

    <form action="<?php echo e(route('secretary.brco.store')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <table class="w-full border-collapse border border-gray-300 text-sm">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">Location</th>
                    <th class="border p-2">Length</th>
                    <th class="border p-2">Date</th>
                    <th class="border p-2">Action Taken</th>
                    <th class="border p-2">Remarks</th>
                    <th class="border p-2">Conducted</th>
                    <th class="border p-2">Photos + Caption</th>
                    <th class="border p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr class="bg-gray-100">
                    <td class="border p-2">
                        <input type="text" name="location" class="w-full border p-1 rounded" required>
                    </td>
                    <td class="border p-2">
                        <input type="text" name="length" class="w-full border p-1 rounded" required>
                    </td>
                    <td class="border p-2">
                        <input type="date" name="date" class="w-full border p-1 rounded" required>
                    </td>
                    <td class="border p-2">
                        <input type="text" name="action_taken" class="w-full border p-1 rounded" required>
                    </td>
                    <td class="border p-2">
                        <input type="text" name="remarks" class="w-full border p-1 rounded">
                    </td>
                    <td class="border p-2">
    <select name="conducted" class="w-full border p-1 rounded" required>
        <option value="" disabled selected>Select</option>
        <option value="1">Yes</option>
        <option value="0">No</option>
    </select>
</td>

                    <td class="border p-2">
                        <input type="file" name="photos[]" multiple class="w-full border p-1 rounded" id="photosInputCreate">
                        <div id="previewContainerCreate" class="flex gap-2 flex-wrap mt-1"></div>
                    </td>
                    <td class="border p-2 text-center">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>

<script>
    const photosInputCreate = document.getElementById('photosInputCreate');
    const previewContainerCreate = document.getElementById('previewContainerCreate');

    photosInputCreate.addEventListener('change', function (e) {
        previewContainerCreate.innerHTML = '';
        Array.from(e.target.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(event) {
                const div = document.createElement('div');
                div.classList.add('flex','flex-col','items-center','relative');

                // Image preview
                const img = document.createElement('img');
                img.src = event.target.result;
                img.classList.add('w-16','h-16','object-cover','rounded','border','mb-1');

                // Caption input
                const caption = document.createElement('input');
                caption.type = 'text';
                caption.name = `photo_captions[]`;
                caption.placeholder = 'Caption';
                caption.classList.add('border','p-1','rounded','text-sm','w-24');

                // Remove button
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.textContent = 'Remove';
                removeBtn.classList.add('text-xs','text-red-600','mt-1','hover:underline');
                removeBtn.addEventListener('click', () => {
                    div.remove();
                    // Remove the file from the input
                    const dt = new DataTransfer();
                    Array.from(photosInputCreate.files)
                        .forEach((f, i) => { if (i !== index) dt.items.add(f); });
                    photosInputCreate.files = dt.files;
                });

                div.appendChild(img);
                div.appendChild(caption);
                div.appendChild(removeBtn);

                previewContainerCreate.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/brco/create.blade.php ENDPATH**/ ?>