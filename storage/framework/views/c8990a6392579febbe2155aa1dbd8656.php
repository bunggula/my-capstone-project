<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document Verified</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4 py-8 sm:py-12 font-sans">

    <div class="bg-white shadow-md rounded-xl p-6 sm:p-8 max-w-md w-full text-center">
        
        <!-- Success Icon -->
        <div class="flex justify-center mb-4">
            <div class="bg-green-100 text-green-600 rounded-full p-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 sm:w-7 sm:h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>

        <!-- Heading -->
        <h2 class="text-lg sm:text-xl font-semibold text-gray-800 mb-2">✅ Document Found</h2>
        <p class="text-gray-600 text-sm sm:text-base mb-6">The following document has been successfully verified.</p>

        <!-- Document Details -->
        <div class="text-left space-y-3 text-sm sm:text-base text-gray-700">
            <p>
                <span class="font-medium">📄 Reference Code:</span>
                <span class="ml-1"><?php echo e($request->reference_code); ?></span>
            </p>
            <p>
                <span class="font-medium">📘 Document Type:</span>
                <span class="ml-1"><?php echo e($request->document_type); ?></span>
            </p>
            <p>
                <span class="font-medium">🙋 Resident:</span>
                <span class="ml-1"><?php echo e($request->resident->first_name); ?> <?php echo e($request->resident->last_name); ?></span>
            </p>
            <p>
                <span class="font-medium">📌 Status:</span>
                <span class="ml-2 inline-block px-2 py-1 rounded-full text-xs sm:text-sm font-medium
                    <?php echo e($request->status === 'approved' ? 'bg-green-100 text-green-700' :
                        ($request->status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                        ($request->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700'))); ?>">
                    <?php echo e(ucfirst($request->status)); ?>

                </span>
            </p>
        </div>
    </div>

</body>
</html>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/verification/valid.blade.php ENDPATH**/ ?>