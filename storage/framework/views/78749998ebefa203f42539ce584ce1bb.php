<div x-show="openViewModal" x-transition x-cloak
     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white w-full max-w-3xl p-6 rounded-lg shadow-lg relative overflow-y-auto max-h-[80vh]">
        <button @click="openViewModal = false"
                class="absolute top-2 right-3 text-gray-600 hover:text-red-500 text-xl z-10">
            &times;
        </button>
        <div x-html="viewData" x-init="$nextTick(() => Alpine.initTree($el))"></div>
    </div>
</div>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/abc/announcements/_view-announcement-modal.blade.php ENDPATH**/ ?>