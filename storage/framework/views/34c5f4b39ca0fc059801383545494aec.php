<!-- Edit Resident Modal -->
<div id="editResidentModal" 
     class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-xl w-full max-w-3xl relative shadow-lg">

        <!-- Close Button -->
        <button onclick="closeEditModal()" 
                class="absolute top-2 right-2 text-gray-500 hover:text-red-500 text-2xl font-bold">×</button>

        <h2 class="text-xl font-bold mb-6">Edit Resident</h2>

        <form id="editResidentForm" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <input type="hidden" name="id" id="editResidentId">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- First Name -->
                <div>
                    <label class="block text-sm font-medium mb-1">First Name</label>
                    <input type="text" name="first_name" id="editFirstName" required
                           class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500">
                </div>

                <!-- Middle Name -->
                <div>
                    <label class="block text-sm font-medium mb-1">Middle Name</label>
                    <input type="text" name="middle_name" id="editMiddleName"
                           class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500">
                </div>

                <!-- Last Name -->
                <div>
                    <label class="block text-sm font-medium mb-1">Last Name</label>
                    <input type="text" name="last_name" id="editLastName" required
                           class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500">
                </div>

                <!-- Suffix -->
                <div>
                    <label class="block text-sm font-medium mb-1">Suffix</label>
                    <input type="text" name="suffix" id="editSuffix"
                           class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500">
                </div>

                <!-- Gender -->
                <div>
                    <label class="block text-sm font-medium mb-1">Sex</label>
                    <select name="gender" id="editGender" required
                            class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500">
                        <option value="">-- Select Gender --</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>

                <!-- Birthdate -->
                <div>
                    <label class="block text-sm font-medium mb-1">Birthdate</label>
                    <input type="date" name="birthdate" id="editBirthdate" required
                           class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500">
                </div>

               

                <!-- Civil Status -->
                <div>
                    <label class="block text-sm font-medium mb-1">Civil Status</label>
                    <select name="civil_status" id="editCivilStatus" required
                            class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500">
                        <option value="">-- Select Civil Status --</option>
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Widow">Widow</option>
                        <option value="Separated">Separated</option>
                    </select>
                </div>

                <!-- Categories -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">Categories</label>
                    <div class="flex flex-wrap gap-4">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="categories[]" value="PWD" class="rounded border-gray-300">
                            <span>PWD</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="categories[]" value="Senior" class="rounded border-gray-300" id="seniorCheckbox">
                            <span>Senior Citizen</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="categories[]" value="Indigenous" class="rounded border-gray-300">
                            <span>Indigenous People</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="categories[]" value="Single Parent" class="rounded border-gray-300">
                            <span>Single Parent</span>
                        </label>
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" name="email" id="editEmail" readonly
                           class="border rounded-lg px-4 py-3 w-full text-sm bg-gray-100 cursor-not-allowed">
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium mb-1">Phone</label>
                    <input type="text" name="phone" id="editPhone" readonly
                           class="border rounded-lg px-4 py-3 w-full text-sm bg-gray-100 cursor-not-allowed">
                </div>

                <!-- Zone -->
                <div>
                    <label class="block text-sm font-medium mb-1">Zone</label>
                    <input type="number" name="zone" id="editZone" min="1" required
                           class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500">
                </div>

                <!-- Voter -->
                <div>
                    <label class="block text-sm font-medium mb-1">Voter</label>
                    <select name="voter" id="editVoter" required
                            class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500">
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end mt-6 gap-4">
                <button type="submit"
                        class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Calculate age
function calculateAge(birthdate) {
    if (!birthdate) return '';
    const birth = new Date(birthdate);
    const today = new Date();
    let age = today.getFullYear() - birth.getFullYear();
    const m = today.getMonth() - birth.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) age--;
    return age;
}

// Populate modal
function populateEditModal(data) {
    const birthInput = document.getElementById('editBirthdate');
    const seniorCheckbox = document.getElementById('seniorCheckbox');
    const ageInput = document.getElementById('editAge');

    // Populate basic fields
    document.getElementById('editResidentId').value = data.id || '';
    document.getElementById('editFirstName').value = data.first_name || '';
    document.getElementById('editMiddleName').value = data.middle_name || '';
    document.getElementById('editLastName').value = data.last_name || '';
    document.getElementById('editSuffix').value = data.suffix || '';
    document.getElementById('editGender').value = data.gender || '';
    document.getElementById('editCivilStatus').value = data.civil_status || '';
    document.getElementById('editEmail').value = data.email || '';
    document.getElementById('editPhone').value = data.phone || '';
    document.getElementById('editZone').value = data.zone || '';
    document.getElementById('editVoter').value = data.voter || 'No';

    // Set birthdate
    birthInput.value = data.birthdate || '';

    // Always display age
    const age = calculateAge(birthInput.value);
    ageInput.value = age !== '' ? age : '';

    // Senior checkbox logic
    if (age >= 60) {
        seniorCheckbox.checked = true;
    } else {
        seniorCheckbox.checked = false;
    }
    seniorCheckbox.disabled = true;

    // Clear other checkboxes
    document.querySelectorAll('#editResidentForm input[name="categories[]"]').forEach(el => {
        if (el.value !== 'Senior') el.checked = false;
    });

    // Populate other categories from DB
    if (data.category) {
        const categories = data.category.split(',').map(c => c.trim().toLowerCase());
        categories.forEach(cat => {
            if (cat === 'senior' || cat === 'senior citizen') return;
            const formatted = cat.charAt(0).toUpperCase() + cat.slice(1);
            const checkbox = document.querySelector(`#editResidentForm input[name="categories[]"][value="${formatted}"]`);
            if (checkbox) checkbox.checked = true;
        });
    }
}

// Open modal
function openEditModal(data) {
    populateEditModal(data);
    document.getElementById('editResidentModal').classList.remove('hidden');
}

// Close modal
function closeEditModal() {
    document.getElementById('editResidentModal').classList.add('hidden');
}
</script>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/residents/edit.blade.php ENDPATH**/ ?>