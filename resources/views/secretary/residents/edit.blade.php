<!-- Edit Resident Modal -->
<div id="editResidentModal" 
     class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-2xl shadow-lg w-full max-w-3xl relative overflow-y-auto max-h-screen">

        <!-- Close Button -->
        <button onclick="closeEditModal()" 
                class="absolute top-3 right-3 text-gray-500 hover:text-red-500 text-2xl font-bold">&times;</button>

        <h2 class="text-2xl font-semibold mb-4 text-gray-800">Edit Resident</h2>

        <form id="editResidentForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="editResidentId">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- First Name -->
                <input type="text" name="first_name" id="editFirstName" placeholder="First Name" required
                       class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                
                <!-- Middle Name -->
                <input type="text" name="middle_name" id="editMiddleName" placeholder="Middle Name"
                       class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">

                <!-- Last Name -->
                <input type="text" name="last_name" id="editLastName" placeholder="Last Name" required
                       class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">

                <!-- Suffix -->
                <input type="text" name="suffix" id="editSuffix" placeholder="Suffix"
                       class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">

                <!-- Gender -->
                <select name="gender" id="editGender" required
                        class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                    <option value="">Sex</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>

                <!-- Birthdate -->
                <input type="date" name="birthdate" id="editBirthdate" placeholder="Birthdate"
                       max="{{ date('Y-m-d') }}" required
                       class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">

                <!-- Civil Status -->
                <select name="civil_status" id="editCivilStatus" required
                        class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                    <option value="">Civil Status</option>
                    <option value="Single">Single</option>
                    <option value="Married">Married</option>
                    <option value="Widowed">Widowed</option>
                    <option value="Separated">Separated</option>
                </select>

                <!-- Categories -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium mb-2">Categories:</label>
                    <div class="flex flex-wrap gap-4">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="categories[]" value="PWD" class="w-5 h-5">
                            PWD
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="categories[]" value="Senior" class="w-5 h-5" id="seniorCheckbox">
                            Senior Citizen
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="categories[]" value="Indigenous" class="w-5 h-5">
                            Indigenous People
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="categories[]" value="Single Parent" class="w-5 h-5">
                            Single Parent
                        </label>
                    </div>
                </div>

                <!-- Email -->
                <input type="email" name="email" id="editEmail" placeholder="Email" readonly
                       class="border rounded-lg px-4 py-3 w-full text-sm bg-gray-100 cursor-not-allowed">

                <!-- Phone -->
                <input type="text" name="phone" id="editPhone" placeholder="Phone Number" maxlength="11"
                       pattern="\d{11}" title="Please enter exactly 11 digits" readonly
                       class="border rounded-lg px-4 py-3 w-full text-sm bg-gray-100 cursor-not-allowed">

                <!-- Zone -->
                <input type="number" name="zone" id="editZone" placeholder="Zone" min="1" required
                       class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">

                <!-- Voter -->
                <select name="voter" id="editVoter" required
                        class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                    <option value="">Voter Status</option>
                    <option value="Yes">Registered</option>
                    <option value="No">Non-Registered</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-4 mt-4">
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
