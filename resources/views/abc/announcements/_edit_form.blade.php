{{-- resources/views/abc/announcements/_edit_form.blade.php --}}
<form action="{{ route('abc.announcements.update', $announcement->id) }}" 
      method="POST" enctype="multipart/form-data" 
      class="space-y-6"
      x-data="announcementForm({
            target: '{{ old('target', $announcement->target) }}',
            details: `{{ old('details', $announcement->details) }}`,
            roleAll: '{{ $announcement->target === "all" ? old("role_all", $announcement->target_role) : "" }}',
            barangayId: '{{ $announcement->target === "specific" ? old("barangay_id", $announcement->barangay_id) : "" }}',
            role: '{{ $announcement->target === "specific" ? old("role", $announcement->target_role) : "" }}'
      })"
      x-init="init()">

    @csrf
    @method('PUT')

    {{-- Title --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Title:</label>
        <input type="text" name="title" 
               value="{{ old('title', $announcement->title) }}"
               class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500" 
               required>
    </div>

    {{-- Details --}}
    <div>
        <label class="block text-sm font-medium mb-1">Details</label>
        <textarea name="details" id="details" rows="3" maxlength="500" required
                  x-model="details"
                  @input="updateCounter()"
                  class="border rounded-lg px-4 py-2 w-full text-sm focus:ring-2 focus:ring-green-500"></textarea>
        <p class="text-xs text-gray-500 mt-1" x-text="detailsCounter"></p>
    </div>

    {{-- Date & Time --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Date:</label>
            <input type="date" name="date"
                   min="{{ now()->toDateString() }}"
                   value="{{ old('date', $announcement->date) }}"
                   class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500"
                   required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Time:</label>
            <input type="time" name="time"
                   value="{{ old('time', $announcement->time) }}"
                   class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500"
                   required>
        </div>
    </div>

    {{-- Target Audience --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Recipients:</label>

        <div class="flex gap-6 items-center mb-3">
            <label class="flex items-center gap-2">
                <input type="radio" name="target" value="all" x-model="target">
                <span>All Barangays</span>
            </label>

            <label class="flex items-center gap-2">
                <input type="radio" name="target" value="specific" x-model="target">
                <span>Specific Barangay and Recipients</span>
            </label>
        </div>

        {{-- For ALL Barangays --}}
        <div x-show="target === 'all'" class="transition">
            <label class="block text-sm font-medium mb-1">Recipients (Optional):</label>
            <select name="role_all" x-model="roleAll"
                    class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500">
                <option value="">-- All Recipients --</option>
                <option value="brgy_captain">Barangay Captain</option>
                <option value="secretary">Secretary</option>
            </select>
        </div>

        {{-- For SPECIFIC --}}
        <div x-show="target === 'specific'" class="grid grid-cols-1 md:grid-cols-2 gap-4 transition">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Barangay:</label>
                <select name="barangay_id" x-model="barangayId"
                        class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Select Barangay --</option>
                    @foreach ($barangays as $brgy)
                        <option value="{{ $brgy->id }}">{{ $brgy->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Recipients:</label>
                <select name="role" x-model="role"
                        class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Select Recipients --</option>
                    <option value="brgy_captain">Barangay Captain</option>
                    <option value="secretary">Secretary</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Existing Images --}}
    @if ($announcement->images->count())
        <div>
            <label class="block text-sm font-medium mb-2">Current Images:</label>
            <p class="text-sm text-gray-500 mb-2">Tick images you want to delete.</p>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($announcement->images as $img)
                    <div class="relative rounded-lg shadow-md bg-gray-100 h-28 flex justify-center items-center overflow-hidden">
                        <img src="{{ asset('storage/' . $img->path) }}" class="object-cover max-w-full max-h-full">

                        <label class="absolute top-2 right-2 bg-white p-1 rounded shadow">
                            <input type="checkbox" name="delete_images[]" value="{{ $img->id }}">
                            <span class="text-xs text-red-600">Delete</span>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Upload New Images --}}
    <div x-data="imageUploader()" x-init="init()">
        <label class="block text-sm font-medium mb-2">Upload New Images (max 3):</label>
        <input type="file" x-ref="input" name="images[]" multiple accept="image/*"
               @change="handleFiles"
               class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500">
        <small class="text-gray-500">You can upload up to 3 images</small>

        <div x-ref="previewContainer" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 mt-4"></div>
    </div>

    {{-- Buttons --}}
    <div class="flex justify-end gap-4">
        <a href="{{ route('abc.announcements.index') }}"
   class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Cancel</a>


        <button type="submit"
                class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700">Update</button>
    </div>
</form>

<script>
function announcementForm(data) {
    return {
        target: data.target,
        details: data.details,
        detailsCounter: '',

        roleAll: data.roleAll,
        barangayId: data.barangayId,
        role: data.role,

        init() {
            this.updateCounter();
        },

        updateCounter() {
            const max = 500;
            this.detailsCounter = (max - this.details.length) + ' characters remaining';
        },

        $watch: {
            target(val) {
                if (val === 'all') {
                    this.role = '';
                    this.barangayId = '';
                } else {
                    this.roleAll = '';
                }
            }
        }
    }
}

function imageUploader() {
    return {
        selectedFiles: [],

        init() {},

        handleFiles(e) {
            const files = Array.from(e.target.files);

            if (files.length + this.selectedFiles.length > 3) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Limit Exceeded',
                    text: 'Maximum of 3 images only.'
                });
                return;
            }

            this.selectedFiles = this.selectedFiles.concat(files);
            this.updatePreview();
        },

        updatePreview() {
            const container = this.$refs.previewContainer;
            const input = this.$refs.input;

            container.innerHTML = '';

            this.selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = e => {
                    const div = document.createElement('div');
                    div.classList = 'relative rounded-lg shadow bg-gray-100 h-28 flex items-center justify-center overflow-hidden';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="object-cover max-w-full max-h-full">
                        <button type="button" class="absolute top-2 right-2 bg-red-600 text-white rounded-full px-2">&times;</button>
                    `;
                    div.querySelector('button').addEventListener('click', () => {
                        this.selectedFiles.splice(index, 1);
                        this.updatePreview();
                    });

                    container.appendChild(div);
                };
                reader.readAsDataURL(file);
            });

            const dt = new DataTransfer();
            this.selectedFiles.forEach(f => dt.items.add(f));
            input.files = dt.files;
        }
    }
}
</script>
