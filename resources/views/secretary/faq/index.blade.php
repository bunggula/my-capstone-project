@extends('layouts.app')

@section('title', 'FAQ Management')

@section('content')
<div x-data="{ openAdd: false, openEdit: null, openView: null }" class="flex h-screen bg-gray-100 overflow-hidden">

    {{-- Sidebar --}}
    @include('partials.secretary-sidebar')

    {{-- Main Content --}}
    <main class="flex-1 flex flex-col overflow-hidden">

        {{-- Header --}}
        @include('partials.secretary-header', ['title' => 'FAQ Management'])

        {{-- Page Content --}}
        <div class="flex-1 overflow-auto p-4 md:p-6">

            {{-- Success/ Error Alerts --}}
            @if (session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: '{{ session('success') }}',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    });
                </script>
            @endif

            @if (session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: '{{ session('error') }}',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    });
                </script>
            @endif

            {{-- Page Heading and Add Button --}}
            <div class="mb-6 flex flex-col md:flex-row justify-between items-center">
                <h1 class="text-2xl font-bold text-blue-900 mb-4 md:mb-0">FAQ Management</h1>
                <button @click="openAdd = true" 
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    + Add FAQ
                </button>
            </div>

            {{-- FAQ Table --}}
            <div class="bg-white shadow rounded-lg p-4">
                <div class="overflow-x-auto">
                    <table class="w-full table-fixed border text-sm">
                        <thead class="bg-blue-600 text-white">
                            <tr>
                                <th class="px-4 py-2 w-12 text-center">#</th>
                                <th class="px-4 py-2 w-1/2">Question</th>
                                <th class="px-4 py-2 w-40">Category</th>
                                <th class="px-4 py-2 w-32 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($faqs as $faq)
                            <tr class="{{ $loop->even ? 'bg-gray-100' : 'bg-white' }} hover:bg-gray-200">
                                <td class="px-4 py-2 border-b text-center">
                                    {{ $faqs->firstItem() + $loop->index }}
                                </td>

                                <td class="px-4 py-2 border-b whitespace-normal break-words">
                                    {{ $faq->question }}
                                </td>

                                <td class="px-4 py-2 border-b text-center">
                                    {{ $faq->category ?? '-' }}
                                </td>

                                <td class="px-2 py-1 border-b text-center whitespace-nowrap">
                                    <div class="inline-flex gap-2">

                                        {{-- View --}}
                                        <button @click="openView = {{ $faq->id }}" 
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7
                                                         -1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>

                                        {{-- Edit --}}
                                        <button @click="openEdit = {{ $faq->id }}" 
                                            class="bg-yellow-100 hover:bg-yellow-200 text-yellow-600 px-2 py-1 rounded text-xs flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                            </svg>
                                        </button>

                                        {{-- Delete --}}
                                        <button @click="deleteFaq({{ $faq->id }}, '{{ addslashes($faq->question) }}')" 
                                            class="bg-red-600 text-white p-2 rounded hover:bg-red-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none"
                                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0a1 1 0 011 1v0a1 1 0 01-1 1H7a1 1 0 01-1-1v0a1 1 0 011-1h10z"/>
                                            </svg>
                                        </button>

                                    </div>
                                </td>
                            </tr>

                            {{-- View Modal --}}
                            <div x-show="openView === {{ $faq->id }}" 
                                 class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" x-cloak>
                                <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
                                    <button @click="openView = null" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800">✕</button>
                                    <h2 class="text-xl font-bold mb-4">View FAQ</h2>
                                    <div class="mb-3">
                                        <label class="block mb-1 font-medium">Question</label>
                                        <p class="border px-3 py-2 rounded bg-gray-100">{{ $faq->question }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="block mb-1 font-medium">Answer</label>
                                        <p class="border px-3 py-2 rounded bg-gray-100">{{ $faq->answer }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="block mb-1 font-medium">Category</label>
                                        <p class="border px-3 py-2 rounded bg-gray-100">{{ $faq->category ?? '-' }}</p>
                                    </div>
                                    <div class="flex justify-end">
                                        <button @click="openView = null" class="px-4 py-2 border rounded hover:bg-gray-100">Close</button>
                                    </div>
                                </div>
                            </div>

                            {{-- Edit Modal --}}
                            <div x-data="{
                                    category: '{{ in_array($faq->category, ['Document Requirements','Payment','Barangay Hours','Barangay Officials']) ? $faq->category : 'Other' }}',
                                    customCategory: '{{ !in_array($faq->category, ['Document Requirements','Payment','Barangay Hours','Barangay Officials']) ? $faq->category : '' }}'
                                }" 
                                x-show="openEdit === {{ $faq->id }}" 
                                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" 
                                x-cloak>
                                <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
                                    <button @click="openEdit = null" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800">✕</button>
                                    <h2 class="text-xl font-bold mb-4">Edit FAQ</h2>
                                    <form action="{{ route('secretary.faq.update', $faq->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label class="block mb-1 font-medium">Question</label>
                                            <input type="text" name="question" value="{{ $faq->question }}" class="w-full border px-3 py-2 rounded" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="block mb-1 font-medium">Answer</label>
                                            <textarea name="answer" class="w-full border px-3 py-2 rounded" rows="3" required>{{ $faq->answer }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="block mb-1 font-medium">Category</label>
                                            <select x-model="category" class="w-full border px-3 py-2 rounded" required>
                                                <option value="">Select category</option>
                                                <option value="Document Requirements">Document Requirements</option>
                                                <option value="Payment">Payment</option>
                                                <option value="Barangay Hours">Barangay Hours</option>
                                                <option value="Barangay Officials">Barangay Officials</option>
                                                <option value="Other">Other</option>
                                            </select>
                                            <input x-show="category === 'Other'" x-model="customCategory" type="text" placeholder="Enter custom category"
                                                   class="w-full border px-3 py-2 mt-2 rounded">
                                            <input type="hidden" name="category" :value="category === 'Other' ? customCategory : category">
                                        </div>
                                        <div class="flex justify-end gap-2">
                                            <button type="button" @click="openEdit = null" class="px-4 py-2 border rounded hover:bg-gray-100">Cancel</button>
                                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            @empty
                            <tr>
                                <td class="border px-4 py-2 text-center" colspan="4">No FAQs found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $faqs->links() }}
                    </div>
                </div>
            </div>

            {{-- Add Modal --}}
            <div x-data="{ category: '', customCategory: '' }" x-show="openAdd" 
                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" x-cloak>
                <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
                    <button @click="openAdd = false" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800">✕</button>
                    <h2 class="text-xl font-bold mb-4">Add New FAQ</h2>
                    <form action="{{ route('secretary.faq.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="block mb-1 font-medium">Question</label>
                            <input type="text" name="question" class="w-full border px-3 py-2 rounded" required>
                        </div>
                        <div class="mb-3">
                            <label class="block mb-1 font-medium">Answer</label>
                            <textarea name="answer" class="w-full border px-3 py-2 rounded" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="block mb-1 font-medium">Category</label>
                            <select x-model="category" class="w-full border px-3 py-2 rounded" required>
                                <option value="">Select category</option>
                                <option value="Document Requirements">Document Requirements</option>
                                <option value="Payment">Payment</option>
                                <option value="Barangay Hours">Barangay Hours</option>
                                <option value="Barangay Officials">Barangay Officials</option>
                                <option value="Other">Other</option>
                            </select>
                            <input x-show="category === 'Other'" x-model="customCategory" type="text" placeholder="Enter custom category"
                                   class="w-full border px-3 py-2 mt-2 rounded">
                            <input type="hidden" name="category" :value="category === 'Other' ? customCategory : category">
                        </div>
                        <div class="flex justify-end gap-2">
                            <button type="button" @click="openAdd = false" class="px-4 py-2 border rounded hover:bg-gray-100">Cancel</button>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
                        </div>
                    </form>
                </div>
            </div>

        </div> {{-- End Page Content --}}
    </main>
</div>

<script>
function deleteFaq(id, question) {
    Swal.fire({
        title: 'Are you sure?',
        html: `You are about to delete the FAQ: <strong>"${question}"</strong>"`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/secretary/faq/${id}`;
            
            const token = document.createElement('input');
            token.type = 'hidden';
            token.name = '_token';
            token.value = '{{ csrf_token() }}';
            form.appendChild(token);
            
            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';
            form.appendChild(method);
            
            document.body.appendChild(form);
            form.submit();
        }
    })
}
</script>

@endsection
