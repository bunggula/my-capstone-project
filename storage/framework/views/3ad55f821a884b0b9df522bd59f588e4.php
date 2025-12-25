<?php
    use Carbon\Carbon;

    $barangay = Auth::user()->barangay ?? null;
    $logo = $barangay && $barangay->logo ? asset('storage/' . $barangay->logo) : asset('assets/images/logo.png');
    $headerTitle = 'SERVICES REPORT' . ($documentType ? ' — ' . strtoupper($documentType) : '');

    // 🗓 Format filter label
    $filterLabel = '';
    if ($dateFilter === 'daily' && !empty($dailyDate)) {
        $filterLabel = 'DAILY — ' . Carbon::parse($dailyDate)->format('F d, Y');
    } elseif ($dateFilter === 'weekly' && !empty($startDate) && !empty($endDate)) {
        $filterLabel = 'WEEKLY — ' . Carbon::parse($startDate)->format('F d, Y') . ' to ' . Carbon::parse($endDate)->format('F d, Y');
    } elseif ($dateFilter === 'monthly' && !empty($month)) {
        $filterLabel = 'MONTHLY — ' . Carbon::parse($month)->format('F Y');
    }

    // 🗂 Collect all unique form_data keys across all requests (excluding 'purpose')
    $formDataKeys = [];
foreach ($completedRequests as $req) {
    if(is_array($req->form_data)) {
        $keys = array_keys($req->form_data);
        $keys = array_diff($keys, ['purpose', 'price']); // exclude 'purpose' at 'price'
        $formDataKeys = array_unique(array_merge($formDataKeys, $keys));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Services Report</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 20px;
            font-size: 14px;
            position: relative;
        }
        .bg-watermark {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('<?php echo e($logo); ?>');
            background-size: 60%;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.20;
            z-index: -1;
        }
        .top-info {
            text-align: left;
            margin-bottom: 10px;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .filter-label {
            text-align: center;
            font-size: 13px;
            margin-bottom: 20px;
            font-style: italic;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        th, td {
            border: 1px solid black;
            padding: 6px;
            text-align: center;
        }
        th {
            background-color: #f3f3f3;
        }
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 80px;
        }
        .signature-block {
            text-align: center;
            width: 40%;
        }
        @media print {
            body { margin: 10px; zoom: 90%; }
            @page { size: A4 portrait; margin: 10mm; }
            .signature-section { margin-top: 100px; }
        }
    </style>
</head>
<body onload="window.print()">
    <!-- Watermark Background -->
    <div class="bg-watermark"></div>

    <!-- Header -->
    <div class="header">
        <h3><?php echo e($headerTitle); ?></h3>
    </div>

    <!-- Date Filter Label -->
    <?php if($filterLabel): ?>
        <div class="filter-label"><?php echo e($filterLabel); ?></div>
    <?php endif; ?>

    <!-- Top Info -->
    <div class="top-info">
        <div><strong>PROVINCE:</strong> PANGASINAN</div>
        <div><strong>MUNICIPALITY:</strong> LAOAC</div>
        <div><strong>BARANGAY:</strong> <?php echo e(strtoupper(Auth::user()->barangay->name ?? 'N/A')); ?></div>
    </div>

    <!-- Table -->
    <?php if($completedRequests->isNotEmpty()): ?>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Resident Name</th>
                <th>Document Type</th>
                <th>Purpose</th>
                <?php $__currentLoopData = $formDataKeys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th><?php echo e(ucwords(str_replace('_', ' ', $key))); ?></th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <th>Price</th>
                <th>Completed At</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $completedRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td><?php echo e($request->resident->full_name ?? 'N/A'); ?></td>
                    <td><?php echo e($request->document_type ?? 'N/A'); ?></td>
                    <td><?php echo e($request->purpose ?? 'N/A'); ?></td>
                    <?php $__currentLoopData = $formDataKeys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $value = $request->form_data[$key] ?? 'N/A';

        // Try parse date kung valid
        if (!empty($value)) {
            try {
                $parsed = \Carbon\Carbon::parse($value);
                // Format: September 31, 2025
                $value = $parsed->format('F d, Y');
            } catch (\Exception $e) {
                // kung hindi valid date, leave as is
            }
        }
    ?>
    <td><?php echo e($value); ?></td>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <td><?php echo e($request->price !== null ? '₱' . number_format($request->price, 2) : 'N/A'); ?></td>
                    <td><?php echo e($request->completed_at ? Carbon::parse($request->completed_at)->format('F d, Y') : 'N/A'); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php
    $totalRequests = $completedRequests->count();
    $totalPrice = $completedRequests->sum(function($request) {
        return $request->price ?? 0;
    });
?>

<!-- Totals -->
<div style="margin-top: 15px; font-weight: bold;">
    <div>Total Requests: <?php echo e($totalRequests); ?></div>
    <div>Total Price: ₱<?php echo e(number_format($totalPrice, 2)); ?></div>
</div>

    <?php else: ?>
        <p>No completed service requests found.</p>
    <?php endif; ?>

    <!-- Signatures -->
    <div class="signature-section">
        <div class="signature-block">
            <div style="border-top: 1px solid #000; margin-bottom: 4px;"></div>
            <strong>Prepared by:</strong><br>
            <?php echo e($secretaryName ?? '__________'); ?><br>
            Barangay Secretary
        </div>

        <div class="signature-block">
            <div style="border-top: 1px solid #000; margin-bottom: 4px;"></div>
            <strong>Attested by:</strong><br>
            <?php echo e($captainName ?? '__________'); ?><br>
            Punong Barangay
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/reports/services-print.blade.php ENDPATH**/ ?>