<div class="space-y-6 p-6 bg-white rounded-2xl shadow-lg max-w-3xl mx-auto">
    <h3 class="text-2xl font-bold text-blue-800 flex items-center gap-2 border-b pb-3 mb-6">
        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
        Official Details
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @php
            $fields = [
                'First Name' => $official->first_name,
                'Middle Name' => $official->middle_name ?: '—',
                'Last Name' => $official->last_name,
                'Suffix' => $official->suffix ?: '—',
                'Email' => $official->email,
                'Birthday' => $official->birthday ? \Carbon\Carbon::parse($official->birthday)->toFormattedDateString() : '—',
                'Gender' => $official->gender ? ucfirst($official->gender) : '—',
                'Position' => $official->position,
                'Barangay' => $official->barangay_name ?? 'N/A',
                'Start Year' => $official->start_year ?: '—',
                'End Year' => $official->end_year ?: '—',
                'Categories' => $official->categories ? implode(', ', json_decode($official->categories)) : '—',
            ];
        @endphp

        @foreach($fields as $label => $value)
        <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
            <p class="text-gray-500 font-semibold uppercase text-xs">{{ $label }}</p>
            <p class="mt-1 text-gray-900 font-medium">{{ $value }}</p>
        </div>
        @endforeach
    </div>
</div>
