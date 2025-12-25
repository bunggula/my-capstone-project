
<div id="editBlotterModal" 
     class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center hidden z-50">
    <div class="bg-white w-full max-w-3xl rounded-lg shadow-lg p-6 relative">

        
        <button onclick="closeModal('editBlotterModal')" 
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl">&times;</button>

        <h2 class="text-xl font-bold text-blue-900 mb-4">Edit Blotter Record</h2>

        <form id="editForm" method="POST" class="space-y-4">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium">Date</label>
                    <input type="date" name="date" id="editDate" class="border p-2 w-full rounded" required>
                </div>
                <div>
                    <label class="block font-medium">Time</label>
                    <input type="time" name="time" id="editTime" class="border p-2 w-full rounded" required>
                </div>
            </div>

            
            <div class="border rounded p-4">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-semibold text-gray-700">Complainants</h3>
                    <button type="button" onclick="addComplainant('edit')" class="text-sm bg-green-600 text-white px-2 py-1 rounded">+ Add</button>
                </div>
                <div id="editComplainantsContainer"></div>
            </div>

            
            <div class="border rounded p-4">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-semibold text-gray-700">Respondents</h3>
                    <button type="button" onclick="addRespondent('edit')" class="text-sm bg-green-600 text-white px-2 py-1 rounded">+ Add</button>
                </div>
                <div id="editRespondentsContainer"></div>
            </div>

            
            <div>
                <label class="block font-medium">Description</label>
                <textarea name="description" id="editDescription" rows="3" class="border p-2 w-full rounded" required></textarea>
            </div>

            
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal('editBlotterModal')" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Cancel</button>
                <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800">Update</button>
            </div>
        </form>
    </div>
</div>


<script>
    let editComplainantCount = 0;
    let editRespondentCount = 0;

    function openEditModal(id) {
        fetch(`/captain/blotter/${id}/edit`)
            .then(res => res.json())
            .then(data => {
                // Fill main fields
                document.getElementById('editForm').action = `/captain/blotter/${id}`;
                document.getElementById('editDate').value = data.date;
                document.getElementById('editTime').value = data.time;
                document.getElementById('editDescription').value = data.description;

                // Clear containers
                const compContainer = document.getElementById('editComplainantsContainer');
                const respContainer = document.getElementById('editRespondentsContainer');
                compContainer.innerHTML = '';
                respContainer.innerHTML = '';
                editComplainantCount = 0;
                editRespondentCount = 0;

                // Populate complainants
                data.complainants.forEach(c => {
                    addComplainant('edit', c);
                });

                // Populate respondents
                data.respondents.forEach(r => {
                    addRespondent('edit', r);
                });

                openModal('editBlotterModal');
            });
    }

    function addComplainant(type = 'add', data = null) {
        const container = type === 'edit' ? document.getElementById('editComplainantsContainer') : document.getElementById('complainants-container');
        const count = type === 'edit' ? editComplainantCount : complainantCount;

        const div = document.createElement('div');
        div.classList.add('grid', 'grid-cols-3', 'gap-3', 'mb-2');

        div.innerHTML = `
            <input type="text" name="${type === 'edit' ? `complainants[${count}][first_name]` : `complainants[${count}][first_name]`}" placeholder="First Name" class="border p-2 rounded" value="${data?.first_name ?? ''}" required>
            <input type="text" name="${type === 'edit' ? `complainants[${count}][middle_name]` : `complainants[${count}][middle_name]`}" placeholder="Middle Name" class="border p-2 rounded" value="${data?.middle_name ?? ''}">
            <input type="text" name="${type === 'edit' ? `complainants[${count}][last_name]` : `complainants[${count}][last_name]`}" placeholder="Last Name" class="border p-2 rounded" value="${data?.last_name ?? ''}" required>
        `;

        container.appendChild(div);
        if(type === 'edit') editComplainantCount++;
        else complainantCount++;
    }

    function addRespondent(type = 'add', data = null) {
        const container = type === 'edit' ? document.getElementById('editRespondentsContainer') : document.getElementById('respondents-container');
        const count = type === 'edit' ? editRespondentCount : respondentCount;

        const div = document.createElement('div');
        div.classList.add('grid', 'grid-cols-3', 'gap-3', 'mb-2');

        div.innerHTML = `
            <input type="text" name="${type === 'edit' ? `respondents[${count}][first_name]` : `respondents[${count}][first_name]`}" placeholder="First Name" class="border p-2 rounded" value="${data?.first_name ?? ''}" required>
            <input type="text" name="${type === 'edit' ? `respondents[${count}][middle_name]` : `respondents[${count}][middle_name]`}" placeholder="Middle Name" class="border p-2 rounded" value="${data?.middle_name ?? ''}">
            <input type="text" name="${type === 'edit' ? `respondents[${count}][last_name]` : `respondents[${count}][last_name]`}" placeholder="Last Name" class="border p-2 rounded" value="${data?.last_name ?? ''}" required>
        `;

        container.appendChild(div);
        if(type === 'edit') editRespondentCount++;
        else respondentCount++;
    }
</script>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/captain/blotter/edit-modal.blade.php ENDPATH**/ ?>