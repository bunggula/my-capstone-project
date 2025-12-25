
<div id="addBlotterModal" 
     class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center hidden z-50">
    <div class="bg-white w-full max-w-3xl rounded-lg shadow-lg p-6 relative">

        
        <button onclick="closeModal('addBlotterModal')" 
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl">&times;</button>

        <h2 class="text-xl font-bold text-blue-900 mb-4">Add Blotter Record</h2>

        
        <form action="<?php echo e(route('captain.blotter.store')); ?>" method="POST" class="space-y-4">
            <?php echo csrf_field(); ?>

            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium">Date</label>
                    <input type="date" name="date" class="border p-2 w-full rounded" required>
                </div>
                <div>
                    <label class="block font-medium">Time</label>
                    <input type="time" name="time" class="border p-2 w-full rounded" required>
                </div>
            </div>

            
            <div class="border rounded p-4">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-semibold text-gray-700">Complainants</h3>
                    <button type="button" onclick="addComplainant()" class="text-sm bg-green-600 text-white px-2 py-1 rounded">+ Add</button>
                </div>
                <div id="complainants-container">
                    <div class="grid grid-cols-3 gap-3 mb-2">
                        <input type="text" name="complainants[0][first_name]" placeholder="First Name" class="border p-2 rounded" required>
                        <input type="text" name="complainants[0][middle_name]" placeholder="Middle Name" class="border p-2 rounded">
                        <input type="text" name="complainants[0][last_name]" placeholder="Last Name" class="border p-2 rounded" required>
                    </div>
                </div>
            </div>

            
            <div class="border rounded p-4">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-semibold text-gray-700">Respondents</h3>
                    <button type="button" onclick="addRespondent()" class="text-sm bg-green-600 text-white px-2 py-1 rounded">+ Add</button>
                </div>
                <div id="respondents-container">
                    <div class="grid grid-cols-3 gap-3 mb-2">
                        <input type="text" name="respondents[0][first_name]" placeholder="First Name" class="border p-2 rounded" required>
                        <input type="text" name="respondents[0][middle_name]" placeholder="Middle Name" class="border p-2 rounded">
                        <input type="text" name="respondents[0][last_name]" placeholder="Last Name" class="border p-2 rounded" required>
                    </div>
                </div>
            </div>

            
            <div>
                <label class="block font-medium">Description</label>
                <textarea name="description" rows="3" class="border p-2 w-full rounded" required></textarea>
            </div>

            
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal('addBlotterModal')" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Cancel</button>
                <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800">Save</button>
            </div>
        </form>
    </div>
</div>


<script>
    let complainantIndex = 1;
    let respondentIndex = 1;

    function addComplainant() {
        const container = document.getElementById('complainants-container');
        const div = document.createElement('div');
        div.classList.add('grid', 'grid-cols-3', 'gap-3', 'mb-2');
        div.innerHTML = `
            <input type="text" name="complainants[${complainantIndex}][first_name]" placeholder="First Name" class="border p-2 rounded" required>
            <input type="text" name="complainants[${complainantIndex}][middle_name]" placeholder="Middle Name" class="border p-2 rounded">
            <input type="text" name="complainants[${complainantIndex}][last_name]" placeholder="Last Name" class="border p-2 rounded" required>
        `;
        container.appendChild(div);
        complainantIndex++;
    }

    function addRespondent() {
        const container = document.getElementById('respondents-container');
        const div = document.createElement('div');
        div.classList.add('grid', 'grid-cols-3', 'gap-3', 'mb-2');
        div.innerHTML = `
            <input type="text" name="respondents[${respondentIndex}][first_name]" placeholder="First Name" class="border p-2 rounded" required>
            <input type="text" name="respondents[${respondentIndex}][middle_name]" placeholder="Middle Name" class="border p-2 rounded">
            <input type="text" name="respondents[${respondentIndex}][last_name]" placeholder="Last Name" class="border p-2 rounded" required>
        `;
        container.appendChild(div);
        respondentIndex++;
    }
</script>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/captain/blotter/add-modal.blade.php ENDPATH**/ ?>