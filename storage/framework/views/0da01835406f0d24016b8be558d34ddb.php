<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['concern']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['concern']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div id="letter-modal-<?php echo e($concern->id); ?>" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden print:block print:relative print:bg-transparent print:inset-auto print:flex-col print:items-start print:justify-start">

    
    <style>
        @media print {
            body * {
                visibility: hidden !important;
            }

            #print-section-<?php echo e($concern->id); ?>,
            #print-section-<?php echo e($concern->id); ?> * {
                visibility: visible !important;
            }

            #print-section-<?php echo e($concern->id); ?> {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                padding: 60px;
                font-family: serif;
                color: black;
            }

            .print\:hidden {
                display: none !important;
            }
        }
    </style>

    <div class="bg-white p-6 rounded-md w-full max-w-3xl relative shadow-lg print:shadow-none print:p-0 print:m-0 print:max-w-none">

        <!-- Close Button -->
        <button class="absolute top-2 right-3 text-gray-500 hover:text-gray-800 text-xl print:hidden"
            onclick="document.getElementById('letter-modal-<?php echo e($concern->id); ?>').classList.add('hidden')">
            &times;
        </button>

        <?php
            $resident = $concern->resident;
            $complainant = $concern->complainant->full_name ?? '________________';
            $meetingDate = $concern->meeting_date ? \Carbon\Carbon::parse($concern->meeting_date) : null;
        ?>

        <!-- Printable Content -->
        <div id="print-section-<?php echo e($concern->id); ?>" class="text-black font-serif text-[14px] leading-relaxed print:px-24 print:pt-12 print:pb-6">
            
            <!-- Centered Header -->
            <div class="text-center mb-6">
                <p>Republic of the Philippines</p>
                <p>Province of Pangasinan</p>
                <p class="font-bold uppercase">Municipality Of Laoac</p>
                <p class="font-bold uppercase">Barangay <?php echo e(auth()->user()->barangay->name); ?></p>
                <p class="font-bold underline mt-2">OFFICE OF THE PUNONG BARANGAY</p>
            </div>

            <!-- Left-Aligned Info -->
            <div class="mb-6">
                <p><?php echo e(now()->format('F d, Y')); ?></p>
               
                <p><?php echo e($resident->address ?? 'Barangay Address'); ?></p>
            </div>

            <!-- Letter Body -->
            <div class="mb-6">
                <p>Dear Ma'am/Sir:</p>
                <p class="mt-4">
                    You are hereby invited to appear before me in person, on the 
                    <strong><?php echo e($meetingDate ? $meetingDate->format('jS') : '____'); ?></strong> day of 
                    <strong><?php echo e($meetingDate ? $meetingDate->format('F') : '__________'); ?></strong>, 
                    <strong><?php echo e($meetingDate ? $meetingDate->format('Y') : '____'); ?></strong> at 
                    <strong>10:00 AM</strong> at the Barangay Hall, for a dialogue/confrontation between you and 
                    <strong><?php echo e($resident->first_name); ?> <?php echo e($resident->middle_name); ?> <?php echo e($resident->last_name); ?></strong>.
                </p>
                <p class="mt-4">Hoping for your willful appearance.</p>
            </div>

            <!-- Right-aligned Signature -->
            <div class="text-right mb-10">
                <p class="mb-2">Respectfully yours,</p>
                <p class="font-bold underline"><?php echo e(auth()->user()->full_name); ?></p>
                <p>Punong Barangay</p>
            </div>

            <!-- Signature Line -->
            <div class="mt-10">
                <p>Received by: ______________________________________</p>
                <p class="text-xs text-gray-600">(Signature over printed name)</p>
            </div>
        </div>

        <!-- Action Button -->
        <div class="mt-4 flex justify-end print:hidden">
            <button onclick="printLetter(<?php echo e($concern->id); ?>)"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded text-sm transition">
                🖨️
            </button>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/partials/letter-modal.blade.php ENDPATH**/ ?>