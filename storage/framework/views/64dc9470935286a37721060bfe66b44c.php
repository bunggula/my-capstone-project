<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Report</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 20px;
            font-size: 14px;
        }

        .top-info {
            text-align: left;
            margin-bottom: 10px;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            margin-top: 10px;
            margin-bottom: 25px;
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

        tfoot input {
            border: none;
            background: transparent;
            font-weight: bold;
            text-align: center;
            width: 100%;
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
            body {
                margin: 10px;
                zoom: 90%;
            }

            @page {
                size: A4 portrait;
                margin: 10mm;
            }

            tfoot input {
                border: none;
                background: transparent;
            }

            .signature-section {
                margin-top: 100px;
            }
        }
    </style>
</head>
<body onload="window.print()">

<?php
    $headerTitle = match ($type) {
        'daily' => 'SUMMARY OF DAILY WASTE GENERATION AND DIVERSION MONITORING SHEET FOR ' . \Carbon\Carbon::parse(request('date'))->format('F d, Y'),
        'weekly' => 'SUMMARY OF WEEKLY WASTE GENERATION AND DIVERSION MONITORING SHEET<br>FROM ' . \Carbon\Carbon::parse(request('start_date'))->format('F d, Y') . ' TO ' . \Carbon\Carbon::parse(request('end_date'))->format('F d, Y'),
        'monthly' => 'SUMMARY OF MONTHLY WASTE GENERATION AND DIVERSION MONITORING SHEET<br>FOR THE MONTH OF ' . strtoupper($monthYear),
        default => 'SUMMARY OF WASTE GENERATION AND DIVERSION MONITORING SHEET',
    };

    $averageLabel = match ($type) {
        'daily' => 'Average Daily Waste Generated',
        'weekly' => 'Average Weekly Waste Generated',
        'monthly' => 'Average Monthly Waste Generated',
        default => 'Average Waste Generated',
    };
?>

<div class="header">
    <h3><?php echo $headerTitle; ?></h3>
</div>

<div class="top-info">
    <div><strong>PROVINCE:</strong> PANGASINAN</div>
    <div><strong>MUNICIPALITY:</strong> LAOAC</div>
    <div><strong>BARANGAY:</strong> <?php echo e(strtoupper($barangay)); ?></div>
</div>

<?php if($reports->isNotEmpty()): ?>
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Biodegradable</th>
            <th>Recyclable</th>
            <th>Residual (Recyclable)</th>
            <th>Residual (Disposal)</th>
            <th>Special</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e(\Carbon\Carbon::parse($report->date_collected)->format('F d, Y')); ?></td>
                <td><?php echo e($report->biodegradable); ?></td>
                <td><?php echo e($report->recyclable); ?></td>
                <td><?php echo e($report->residual_recyclable); ?></td>
                <td><?php echo e($report->residual_disposal); ?></td>
                <td><?php echo e($report->special); ?></td>
                <td><?php echo e($report->remarks); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>

    <tfoot>
        <tr>
            <th><?php echo e($averageLabel); ?></th>
            <td><input type="text" name="avg_biodegradable" value=""></td>
            <td><input type="text" name="avg_recyclable" value=""></td>
            <td><input type="text" name="avg_residual_recyclable" value=""></td>
            <td><input type="text" name="avg_residual_disposal" value=""></td>
            <td><input type="text" name="avg_special" value=""></td>
            <td></td>
        </tr>
    </tfoot>
</table>
<?php else: ?>
    <p>No records found.</p>
<?php endif; ?>

<!-- Signatures -->
<div class="signature-section">
    <div class="signature-block">
        <div style="border-top: 1px solid #000; margin-bottom: 4px; width: 100%;"></div>
        <strong>Prepared by:</strong><br>
        <?php echo e($secretaryName ?? '__________'); ?><br>
        Barangay Secretary
    </div>

    <div class="signature-block">
        <div style="border-top: 1px solid #000; margin-bottom: 4px; width: 100%;"></div>
        <strong>Attested by:</strong><br>
        <?php echo e($captainName ?? '__________'); ?><br>
        Punong Barangay
    </div>
</div>

</body>
</html>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/reports/print.blade.php ENDPATH**/ ?>