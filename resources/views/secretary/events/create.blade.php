{{-- secretary/events/create.blade.php --}}

<h2 class="text-xl font-bold mb-4">Add Event </h2>

<form method="POST" action="{{ route('secretary.events.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
        <!-- Event Title -->
        <div class="col-span-2">
            <label class="block text-sm font-medium mb-1">Event Title</label>
            <input type="text" name="title" required
                   class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
        </div>

        <!-- Date -->
        <div>
            <label class="block text-sm font-medium mb-1">Date</label>
            <input type="date" name="date" id="event-date" required
                   class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
        </div>

        <!-- Time -->
        <div>
            <label class="block text-sm font-medium mb-1">Time</label>
            <input type="time" name="time" required
                   class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
        </div>

        <!-- Venue -->
        <div class="col-span-2">
            <label class="block text-sm font-medium mb-1">Venue</label>
            <input type="text" name="venue" required
                   class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
        </div>

        <!-- Details -->
        <div class="col-span-2">
    <label class="block text-sm font-medium mb-1">Details</label>
    <textarea name="details" id="details" rows="3" maxlength="500" required
              class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none"></textarea>
    <p id="details-counter" class="text-xs text-gray-500 mt-1">500 characters remaining</p>
</div>

        <!-- Upload Images -->
        <div class="col-span-2">
            <label class="block text-sm font-medium mb-1">Upload Images</label>
            <input type="file" name="images[]" multiple accept="image/*"
                   class="border rounded-lg px-4 py-2 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none"
                   onchange="previewImages(event)" id="image-input">
            <div id="image-preview" class="mt-3 grid grid-cols-4 sm:grid-cols-6 gap-2"></div>
        </div>
    </div>

    <!-- Buttons -->
    <div class="mt-6 flex justify-end gap-4">
        <button type="button"
                class="px-6 py-3 bg-gray-400 text-white font-medium rounded-lg hover:bg-gray-500 transition-colors"
                @click="
                    addModal = false;
                    document.querySelector('form').reset();
                    document.getElementById('image-preview').innerHTML = '';
                ">
            Cancel
        </button>

        <button type="submit"
                class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors">
            Save Event
        </button>
    </div>
</form>

{{-- Scripts for preview and disabling past dates --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const dateInput = document.getElementById('event-date');
    const today = new Date().toISOString().split('T')[0];
    dateInput.min = today;
});

window.selectedFiles = []; // Global array
function previewImages(event) {
    const preview = document.getElementById('image-preview');
    let newFiles = Array.from(event.target.files);

    // Check if total exceeds 3
    if ((window.selectedFiles?.length || 0) + newFiles.length > 3) {

        // Immediately clear the input so modal doesn't react
        event.target.value = '';

        // Show SweetAlert asynchronously
        setTimeout(() => {
            Swal.fire({
                icon: 'warning',
                title: 'Limit Exceeded',
                text: 'You can only upload up to 3 images.',
            });
        }, 10); // tiny delay ensures modal click events don't catch it

        return;
    }

    // Merge files
    window.selectedFiles = (window.selectedFiles || []).concat(newFiles);

    // Render previews
    preview.innerHTML = '';
    window.selectedFiles.forEach((file, index) => {
        if (!file.type.startsWith('image/')) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            const container = document.createElement('div');
            container.className = 'relative w-20 h-20';
            container.dataset.index = index;
            container.innerHTML = `
                <img src="${e.target.result}" class="w-20 h-20 object-cover rounded-lg border shadow">
                <button type="button" class="absolute top-0 right-0 bg-red-600 text-white text-xs px-1 rounded-full hover:bg-red-700">&times;</button>
            `;
            container.querySelector('button').onclick = () => removeImage(index, container);
            preview.appendChild(container);
        };
        reader.readAsDataURL(file);
    });

    updateInputFiles();
}


function removeImage(index, container) {
    window.selectedFiles.splice(index, 1);
    container.remove();
    updateInputFiles();

    document.querySelectorAll('#image-preview > div').forEach((div, idx) => {
        div.dataset.index = idx;
        div.querySelector('button').onclick = () => removeImage(idx, div);
    });
}

function updateInputFiles() {
    const dt = new DataTransfer();
    window.selectedFiles.forEach(f => dt.items.add(f));
    document.getElementById('image-input').files = dt.files;
}



function updateInputFiles() {
    const dt = new DataTransfer();
    window.selectedFiles.forEach(f => dt.items.add(f));
    document.getElementById('image-input').files = dt.files;
}

// Details character counter
const details = document.getElementById('details');
const counter = document.getElementById('details-counter');
const maxLength = details.getAttribute('maxlength');

details.addEventListener('input', () => {
    const remaining = maxLength - details.value.length;
    counter.textContent = `${remaining} characters remaining`;
});
</script>
