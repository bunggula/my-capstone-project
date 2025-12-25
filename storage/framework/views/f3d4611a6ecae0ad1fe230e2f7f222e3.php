
<div class="mb-4">
    <label class="block font-semibold">Title:</label>
    <input type="text" name="title" x-model="editFormData.title" class="w-full border rounded px-3 py-2" required>
</div>

<div class="mb-4">
    <label class="block font-semibold">Details:</label>
    <textarea name="details" rows="4" x-model="editFormData.details" class="w-full border rounded px-3 py-2" required></textarea>
</div>

<div class="mb-4">
    <label class="block font-semibold">Date:</label>
    <input type="date" name="date" x-model="editFormData.date" class="w-full border rounded px-3 py-2" required>
</div>

<div class="mb-4">
    <label class="block font-semibold">Time:</label>
    <input type="time" name="time" x-model="editFormData.time" class="w-full border rounded px-3 py-2" required>
</div>

<div class="mb-4">
    <label class="block font-semibold mb-1">Target:</label>
    <div class="flex gap-4 items-center">
        <label>
            <input type="radio" name="target" value="all" x-model="editFormData.target">
            <span class="ml-1">All Barangays</span>
        </label>
        <label>
            <input type="radio" name="target" value="specific" x-model="editFormData.target">
            <span class="ml-1">Specific Barangay</span>
        </label>
    </div>
    <select name="barangay_id" class="mt-2 border rounded px-3 py-2 w-full" x-model="editFormData.barangay_id">
        <option value="">-- Select Barangay --</option>
        <?php $__currentLoopData = $barangays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barangay): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($barangay->id); ?>"><?php echo e($barangay->name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>

<div class="mb-4">
    <label class="block font-semibold">Upload New Images:</label>
    <input type="file" name="images[]" multiple accept="image/*" class="w-full border rounded px-3 py-2">
</div>

<div class="flex justify-end mt-4 gap-2">
    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">Update</button>
    <button type="button" @click="openEdit = false" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded shadow">Cancel</button>
</div>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/abc/announcements/_edit.blade.php ENDPATH**/ ?>