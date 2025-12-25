<form action="<?php echo e(route('abc.residents.store')); ?>" method="POST" class="space-y-8">
    <?php echo csrf_field(); ?>
    <?php echo $__env->make('abc.residents._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex justify-end">
        <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-md shadow transition duration-150 ease-in-out">
            Save Resident
        </button>
    </div>
</form>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/abc/residents/create.blade.php ENDPATH**/ ?>