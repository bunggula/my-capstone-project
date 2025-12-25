<div x-show="showCreateModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6 relative" @click.outside="showCreateModal = false">
        
        <!-- Close Button (X) -->
        <button type="button" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600"
                @click="showCreateModal = false">
            ✕
        </button>

        <h2 class="text-xl font-bold mb-4">Submit Project Proposal</h2>

        <form x-ref="proposalForm" action="{{ route('captain.proposal.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Project Title -->
                <div class="col-span-2">
                    <label for="title" class="block text-sm font-medium mb-1">Project Title</label>
                    <input type="text" name="title" id="title" required
                           placeholder="e.g. Project BRIGHT"
                           class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                </div>

                <!-- Rationale -->
                <div class="col-span-2">
                    <label for="description" class="block text-sm font-medium mb-1">Rationale</label>
                    <textarea name="description" id="description" rows="4" required
                              placeholder="Explain the issue or need your project addresses."
                              class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none"></textarea>
                </div>

                <!-- Source of Fund -->
                <div class="col-span-2 md:col-span-1">
                    <label for="source_of_fund" class="block text-sm font-medium mb-1">Source of Fund</label>
                    <input type="text" name="source_of_fund" id="source_of_fund"
                           placeholder="e.g. SK Fund, Barangay Fund, Donors"
                           class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                </div>

                <!-- Target Date -->
                <div class="col-span-2 md:col-span-1">
                    <label for="target_date" class="block text-sm font-medium mb-1">Target Date</label>
                    <input type="date" name="target_date" id="target_date" required
                           class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none"
                           min="{{ \Carbon\Carbon::now()->timezone('Asia/Manila')->toDateString() }}">
                </div>

                <!-- Submitter Type Dropdown -->
                <div class="col-span-2">
                    <label for="submitted_by_type" class="block text-sm font-medium mb-1">Submitting As</label>
                    <select name="submitted_by_type" id="submitted_by_type" required
                            class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                        <option value="barangay_captain">Barangay Captain</option>
                        <option value="sk_chairman">SK Chairman</option>
                    </select>
                </div>
            </div>

            <!-- Buttons -->
            <div class="mt-6 flex justify-end gap-4">
                <button type="button"
                        class="px-6 py-3 bg-gray-400 text-white font-medium rounded-lg hover:bg-gray-500 transition-colors"
                        @click="showCreateModal = false; $refs.proposalForm.reset();">
                    Cancel
                </button>

                <button type="submit"
                        class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    Submit
                </button>
            </div>
        </form>
    </div>
</div>
