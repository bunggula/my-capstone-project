<!-- resources/views/abc/announcements/create.blade.php -->
<div class="bg-white p-6 rounded-2xl shadow-lg w-full max-w-3xl mx-auto">
    <h3 class="text-2xl font-semibold mb-6 text-gray-800">Create Announcement</h3>

    <form action="<?php echo e(route('abc.announcements.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
        <?php echo csrf_field(); ?>

        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Title:</label>
            <input type="text" name="title" 
                   class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                   required>
        </div>

        
<div class="col-span-2">
    <label class="block text-sm font-medium mb-1">Details</label>
    <textarea name="details" id="details" rows="2" maxlength="500" required
              class="border rounded-lg px-4 py-2 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none h-12"></textarea>
    <p id="details-counter" class="text-xs text-gray-500 mt-1">500 characters remaining</p>
</div>

        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date:</label>
                <input type="date" name="date" min="<?php echo e(now()->toDateString()); ?>" 
                       class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                       required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Time:</label>
                <input type="time" name="time" 
                       class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                       required>
            </div>
        </div>

      

        
        <div x-data="{ maxFiles: 3 }">
            <label class="block text-sm font-medium text-gray-700 mb-1">Images (max 3):</label>
            <input 
                type="file" 
                id="imageInput" 
                name="images[]" 
                multiple 
                accept="image/*" 
                class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
            >

            <!-- Preview Container -->
            <div id="previewContainer" class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-4"></div>

            <p id="errorMsg" class="text-red-500 text-sm mt-2 hidden">
                You can only upload up to 3 images.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">




<div id="role-all-container">
    <label class="block text-sm font-medium text-gray-700 mb-1">Recipients (Optional):</label>
    <select name="role_all" 
            class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
        <option value="">-- All Recipients --</option>
        <option value="brgy_captain">Barangay Captain</option>
        <option value="secretary">Secretary</option>
    </select>
</div>

</div>


<div class="mt-4">
<label class="block text-sm font-medium text-gray-700 mb-2">Recipients:</label>

<div class="flex gap-6 items-center mb-3">
    <label class="flex items-center gap-2">
        <input type="radio" name="target" value="all" checked class="w-4 h-4">
        <span>All Barangays</span>
    </label>
    <label class="flex items-center gap-2">
        <input type="radio" name="target" value="specific" class="w-4 h-4">
        <span>Specific Barangay and Recipients</span>
    </label>
</div>


<div id="target-filters" class="grid grid-cols-1 md:grid-cols-2 gap-4 hidden">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Barangay:</label>
        <select name="barangay_id" 
                class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            <option value="">-- Select Barangay --</option>
            <?php $__currentLoopData = $barangays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barangay): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($barangay->id); ?>"><?php echo e($barangay->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Recipients:</label>
        <select name="role" 
                class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            <option value="">-- Select Recipients --</option>
            <option value="brgy_captain">Barangay Captain</option>
            <option value="secretary">Secretary</option>
        </select>
    </div>
</div>
</div>

        
        <div class="flex justify-end gap-4">
            <a href="<?php echo e(route('abc.announcements.index')); ?>"
               class="px-6 py-3 bg-gray-500 text-white font-medium rounded-lg hover:bg-gray-600 transition-colors">
                Cancel
            </a>
            <button type="submit"
                    class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors">
                Post
            </button>
        </div>
    </form>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const postedBySelect = document.querySelector('select[name="posted_by"]');
    const allRadio = document.querySelector('input[name="target"][value="all"]');
    const specificRadio = document.querySelector('input[name="target"][value="specific"]');
    const targetFilters = document.getElementById('target-filters');
    const roleAllSelect = document.querySelector('select[name="role_all"]');
    const roleSelect = document.querySelector('select[name="role"]');
    const barangaySelect = document.querySelector('select[name="barangay_id"]');
    const roleAllContainer = document.getElementById('role-all-container');
    const form = document.querySelector('form');
    const imageInput = document.getElementById('imageInput');
    const previewContainer = document.getElementById('previewContainer');
     
    let selectedFiles = [];

    // --- Toggle visibility based on radio + posted_by ---
    function toggleTargetFields() {
    const isSpecific = specificRadio.checked;

    // Show/hide specific fields
    targetFilters.classList.toggle('hidden', !isSpecific);

    // Show/hide "All recipients" dropdown
    roleAllContainer.classList.toggle('hidden', isSpecific);

    if (isSpecific) {
        // Deselect "All recipients" if switching to specific
        roleAllSelect.value = "";
    } else {
        // Deselect specific selections if switching to all
        roleSelect.value = "";
        barangaySelect.value = "";
        roleSelect.disabled = true; // optional: keep disabled if all selected
    }

    // Enable/disable fields
    barangaySelect.disabled = !isSpecific;
    barangaySelect.required = isSpecific;
    roleSelect.disabled = !isSpecific || barangaySelect.value === "";
}

    if (postedBySelect) postedBySelect.addEventListener('change', toggleTargetFields);
    allRadio.addEventListener('change', toggleTargetFields);
    specificRadio.addEventListener('change', toggleTargetFields);
    toggleTargetFields(); // initial state setup

    // --- Image Preview & Remove Logic ---
    imageInput.addEventListener('change', function (e) {
        let files = Array.from(e.target.files);

        if (files.length + selectedFiles.length > 3) {
            Swal.fire({
                icon: 'warning',
                title: 'Limit Reached',
                text: 'You can only upload up to 3 images.',
                confirmButtonColor: '#3085d6',
            });
            files = files.slice(0, 3 - selectedFiles.length);
        }

        selectedFiles = selectedFiles.concat(files); 
        renderPreviews();

        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => dataTransfer.items.add(file));
        imageInput.files = dataTransfer.files;
    });

    function renderPreviews() {
        previewContainer.innerHTML = '';
        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function (e) {
                const div = document.createElement('div');
                div.classList = "relative group";
                div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-20 object-contain rounded-lg shadow-md">
                    <button type="button" data-index="${index}" 
                        class="absolute top-2 right-2 bg-red-600 text-white text-xs px-2 py-1 rounded-full shadow hover:bg-red-700 transition">
                        &times;
                    </button>
                `;
                previewContainer.appendChild(div);
            }
            reader.readAsDataURL(file);
        });
    }

    previewContainer.addEventListener('click', function (e) {
        if (e.target.tagName === "BUTTON") {
            const index = e.target.getAttribute('data-index');
            Swal.fire({
                title: 'Remove this image?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, remove it'
            }).then(result => {
                if (result.isConfirmed) {
                    selectedFiles.splice(index, 1);
                    const dataTransfer = new DataTransfer();
                    selectedFiles.forEach(file => dataTransfer.items.add(file));
                    imageInput.files = dataTransfer.files;
                    renderPreviews();
                }
            });
        }
    });

    // --- Details character counter ---
    const textarea = document.getElementById('details');
    const counter = document.getElementById('details-counter');
    const maxLength = textarea.getAttribute('maxlength');

    textarea.addEventListener('input', () => {
        const remaining = maxLength - textarea.value.length;
        counter.textContent = `${remaining} characters remaining`;
    });

    // --- Auto-disable "Recipient" if no barangay selected ---
    barangaySelect.addEventListener('change', () => {
        if (barangaySelect.value.trim() === "") {
            roleSelect.value = "";
            roleSelect.disabled = true;
        } else {
            roleSelect.disabled = false;
        }
    });

    if (barangaySelect.value.trim() === "") {
        roleSelect.disabled = true;
    }

    // 🔹 FORM VALIDATION (SweetAlert2 popup)
    form.addEventListener('submit', function (e) {
        const isSpecific = specificRadio.checked;
        const barangayValue = barangaySelect.value.trim();
        const roleValue = roleSelect.value.trim();

        if (isSpecific) {
            // ❌ Case 1: may recipient pero walang barangay
            if (!barangayValue && roleValue) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Barangay',
                    text: 'Please select a Barangay when choosing a Recipient.',
                    confirmButtonColor: '#3085d6',
                });
                barangaySelect.focus();
                return false;
            }

            // ❌ Case 2: walang barangay at walang recipient
            if (!barangayValue && !roleValue) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Incomplete Fields',
                    text: 'Please select at least a Barangay before posting.',
                    confirmButtonColor: '#3085d6',
                });
                barangaySelect.focus();
                return false;
            }

            // ✅ Case 3: Barangay ok or Barangay + Role ok
        }
    });
});
</script>

<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/abc/announcements/_form.blade.php ENDPATH**/ ?>