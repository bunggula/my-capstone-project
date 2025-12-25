<form action="<?php echo e(route('abc.announcements.update', $announcement->id)); ?>" 
      method="POST" enctype="multipart/form-data" 
      class="space-y-6" 
      x-data="editAnnouncement()" 
      x-init="init()">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>

    
    <div>
        <input type="text" name="title" 
               value="<?php echo e(old('title', $announcement->title)); ?>" 
               placeholder="Title"
               class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none" 
               required>
    </div>

    
    <div>
        <textarea name="details" rows="4" placeholder="Details"
                  class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none" 
                  required><?php echo e(old('details', $announcement->details)); ?></textarea>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <input type="date" name="date" 
               value="<?php echo e(old('date', $announcement->date)); ?>" 
               class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none" 
               required>
        <input type="time" name="time" 
               value="<?php echo e(old('time', $announcement->time)); ?>" 
               class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none" 
               required>
    </div>
    
<div>
    <label class="block text-sm font-medium mb-1">Posted By:</label>
    <select name="posted_by" 
            class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
        <option value="abc_president" <?php echo e($announcement->posted_by === 'abc_president' ? 'selected' : ''); ?>>
            ABC President
        </option>
        <option value="sk_president" <?php echo e($announcement->posted_by === 'sk_president' ? 'selected' : ''); ?>>
            SK President
        </option>
    </select>
    <p class="text-xs text-gray-500 mt-1">Select who posted this announcement.</p>
</div>


    
    <div x-data="{ target: '<?php echo e(old('target', $announcement->target)); ?>' }">
        <label class="block text-sm font-medium mb-2">Target Audience:</label>
        <div class="flex gap-4 items-center mb-2">
            <label class="flex items-center gap-2">
                <input type="radio" name="target" value="all" x-model="target" class="w-4 h-4">
                All Barangays
            </label>
            <label class="flex items-center gap-2">
                <input type="radio" name="target" value="specific" x-model="target" class="w-4 h-4">
                Specific Barangay + Role
            </label>
        </div>

        
        <div x-show="target === 'all'" class="mt-2" x-transition>
            <label class="block text-sm font-medium mb-1">Role (Optional):</label>
            <select name="role_all"
                    class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                <option value="">-- All Roles --</option>
                <option value="brgy_captain" <?php echo e($announcement->target_role == 'brgy_captain' ? 'selected' : ''); ?>>Barangay Captain</option>
                <option value="secretary" <?php echo e($announcement->target_role == 'secretary' ? 'selected' : ''); ?>>Secretary</option>
            </select>
        </div>

        
        <div x-show="target === 'specific'" class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2" x-transition>
            <select name="barangay_id"
                    class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                <option value="">-- Select Barangay --</option>
                <?php $__currentLoopData = $barangays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barangay): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($barangay->id); ?>" <?php echo e($announcement->barangay_id == $barangay->id ? 'selected' : ''); ?>>
                        <?php echo e($barangay->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <select name="role"
                    class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                <option value="">-- Select Role --</option>
                <option value="brgy_captain" <?php echo e($announcement->target_role == 'brgy_captain' ? 'selected' : ''); ?>>Barangay Captain</option>
                <option value="secretary" <?php echo e($announcement->target_role == 'secretary' ? 'selected' : ''); ?>>Secretary</option>
            </select>
        </div>
    </div>

    
    <?php if($announcement->images->count()): ?>
        <div>
            <label class="block text-sm font-medium mb-2">Current Images:</label>
            <p class="text-sm text-gray-500 mb-2">Tick images you want to delete.</p>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                <?php $__currentLoopData = $announcement->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="relative group overflow-hidden rounded-lg shadow-md">
                        <img src="<?php echo e(asset('storage/' . $img->path)); ?>" class="w-full h-28 object-cover rounded">
                        <div class="absolute top-2 right-2 bg-white bg-opacity-90 rounded-md px-2 py-1 shadow">
                            <input type="checkbox" name="delete_images[]" value="<?php echo e($img->id); ?>">
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endif; ?>

    
    <div>
        <label class="block text-sm font-medium mb-2">Upload New Images (max 3):</label>
        <input type="file" id="editImageInput" name="images[]" multiple accept="image/*"
               class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
        <small class="text-gray-500">You can select up to 3 images (JPEG, PNG, max 2MB each)</small>

        <!-- Preview Container -->
        <div id="editPreviewContainer" class="grid grid-cols-2 sm:grid-cols-3 gap-3 mt-3"></div>
    </div>

    
    <div class="flex justify-end gap-4">
        <button type="button" @click="openEditModal = false"
                class="px-6 py-3 bg-gray-500 text-white font-medium rounded-lg hover:bg-gray-600 transition-colors">
            Cancel
        </button>
        <button type="submit"
                class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors">
            Update
        </button>
    </div>
</form>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function editAnnouncement() {
    return {
        selectedFiles: [],
        init() {
            const imageInput = document.getElementById('editImageInput');
            const previewContainer = document.getElementById('editPreviewContainer');

            imageInput.addEventListener('change', (e) => {
                let files = Array.from(e.target.files);

                if (files.length + this.selectedFiles.length > 3) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Limit Exceeded',
                        text: 'You can only upload up to 3 images.',
                    });
                    files = files.slice(0, 3 - this.selectedFiles.length);
                }

                this.selectedFiles = this.selectedFiles.concat(files);
                this.renderPreviews(previewContainer, imageInput);
            });
        },
        renderPreviews(container, input) {
            container.innerHTML = '';
            this.selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const div = document.createElement('div');
                    div.className = 'relative group';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-28 object-cover rounded shadow-md">
                        <button type="button" data-index="${index}"
                            class="absolute top-2 right-2 bg-red-600 text-white text-xs px-2 py-1 rounded-full shadow hover:bg-red-700 transition">
                            &times;
                        </button>
                    `;
                    container.appendChild(div);
                };
                reader.readAsDataURL(file);
            });

            // Update file input
            const dataTransfer = new DataTransfer();
            this.selectedFiles.forEach(f => dataTransfer.items.add(f));
            input.files = dataTransfer.files;
        },
        removeFile(index, container, input) {
            Swal.fire({
                title: 'Remove this image?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, remove',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    this.selectedFiles.splice(index, 1);
                    this.renderPreviews(container, input);
                }
            });
        }
    }
}

// Handle remove click dynamically
document.addEventListener('click', function(e) {
    if(e.target.closest('#editPreviewContainer button')) {
        const btn = e.target.closest('button');
        const index = parseInt(btn.getAttribute('data-index'));
        const input = document.getElementById('editImageInput');
        const container = document.getElementById('editPreviewContainer');
        const alpineComponent = Alpine.$data(document.querySelector('form[x-data="editAnnouncement()"]'));
        alpineComponent.removeFile(index, container, input);
    }
});
</script>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/abc/announcements/edit.blade.php ENDPATH**/ ?>