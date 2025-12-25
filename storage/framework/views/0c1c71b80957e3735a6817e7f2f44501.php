
<div id="viewBlotterModal" 
     class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center hidden z-50">
    <div class="bg-white w-full max-w-2xl rounded-lg shadow-lg p-6 relative">

        
        <button onclick="closeModal('viewBlotterModal')" 
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl">&times;</button>

        <h2 class="text-xl font-bold text-blue-900 mb-4">View Blotter Details</h2>

        <div id="viewContent" class="text-gray-700 space-y-2">
            <p><strong>Date:</strong> <span id="viewDate"></span></p>
            <p><strong>Time:</strong> <span id="viewTime"></span></p>

            <div>
                <strong>Complainants:</strong>
                <ul id="viewComplainants" class="list-disc ml-6"></ul>
            </div>

            <div>
                <strong>Respondents:</strong>
                <ul id="viewRespondents" class="list-disc ml-6"></ul>
            </div>

            <div>
                <strong>Description:</strong>
                <p id="viewDescription" class="bg-gray-50 p-2 rounded border"></p>
            </div>
        </div>
    </div>
</div>


<script>
   function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}

function openViewModal(id) {
    fetch(`/captain/blotter/${id}`)
        .then(res => res.json())
        .then(data => {
            // Fill in the fields
            document.getElementById('viewDate').innerText = data.date;
            document.getElementById('viewTime').innerText = data.time;
            document.getElementById('viewDescription').innerText = data.description;

            // Clear old lists
            const compList = document.getElementById('viewComplainants');
            const respList = document.getElementById('viewRespondents');
            compList.innerHTML = '';
            respList.innerHTML = '';

            // Add new items
            data.complainants.forEach(c => {
                compList.innerHTML += `<li>${c.first_name} ${c.middle_name ?? ''} ${c.last_name}</li>`;
            });
            data.respondents.forEach(r => {
                respList.innerHTML += `<li>${r.first_name} ${r.middle_name ?? ''} ${r.last_name}</li>`;
            });

            // Show modal
            openModal('viewBlotterModal');
        })
        .catch(err => {
            console.error('Error fetching blotter data:', err);
            alert('Failed to load blotter details.');
        });
}

</script>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/captain/blotter/view-modal.blade.php ENDPATH**/ ?>