<form action="<?php echo e(route('captain.proposals.store')); ?>" method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>

    <div class="mb-4">
        <label for="title" class="block text-sm font-medium text-gray-700">Project Title</label>
        <input type="text" name="title" id="title" required class="w-full border rounded px-3 py-2 mt-1" placeholder="e.g. Project BRIGHT">
    </div>

    <div class="mb-4">
        <label for="description" class="block text-sm font-medium text-gray-700">Rationale</label>
        <textarea name="description" id="description" rows="4" required class="w-full border rounded px-3 py-2 mt-1" placeholder="Explain the issue or need your project addresses."></textarea>
    </div>

    <div class="mb-4">
        <label for="source_of_fund" class="block text-sm font-medium text-gray-700">Source of Fund</label>
        <input type="text" name="source_of_fund" id="source_of_fund" class="w-full border rounded px-3 py-2 mt-1" placeholder="e.g. SK Fund, Barangay Fund, Donors">
    </div>

    <div class="mb-4">
        <label for="target_date" class="block text-sm font-medium text-gray-700">Target Date</label>
        <input type="date" name="target_date" id="target_date" class="w-full border rounded px-3 py-2 mt-1">
    </div>

    <div class="flex justify-end space-x-2 mt-4">
        <button type="button" @click="$dispatch('close')" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Submit Proposal</button>
    </div>
</form>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/captain/proposals/create.blade.php ENDPATH**/ ?>