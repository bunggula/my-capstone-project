<?php
    $barangay = $request->resident->barangay ?? null;
    $logo = $barangay && $barangay->logo
        ? asset('storage/' . $barangay->logo)
        : asset('assets/images/logo.png');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            padding: 40px;
            font-size: 13px;
            line-height: 1.6;
            background: white;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .header .logo {
            width: 80px;
            height: 80px;
        }

        .header .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .header .text {
            flex: 1;
            text-align: center;
        }

        .title {
            font-weight: bold;
            text-align: center;
            margin: 20px 0 10px;
            font-size: 15px;
            text-transform: uppercase;
        }

        .main-section {
            display: flex;
            margin-top: 20px;
            border-top: 1px solid #ccc;
            padding-top: 20px;
        }

        .officials-section {
            width: 35%;
            border-right: 1px solid #ccc;
            padding-right: 15px;
        }

        .officials-title {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .officials-list {
            font-size: 13px;
        }

        .officials-list p,
        .officials-list li {
            margin: 4px 0;
        }

        .officials-list ul {
            padding-left: 20px;
        }

        .content-section {
            width: 65%;
            padding-left: 20px;
        }

        .content-section .content {
            text-align: justify;
            font-size: 13px;
        }

        .signature {
            margin-top: 40px;
            text-align: right;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
        }

        .no-print {
            margin-top: 30px;
        }

        /* Watermark styles */
        .watermark-wrapper {
            position: relative;
        }

        .watermark-logo {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.07;
            z-index: 0;
            width: 60%;
            max-width: 400px;
            pointer-events: none;
        }

        .watermark-wrapper > *:not(.watermark-logo) {
            position: relative;
            z-index: 1;
        }

        @media print {
            .no-print {
                display: none;
            }

            @page {
                margin: 15mm;
            }
        }
    </style>
</head>
<body>

<div class="watermark-wrapper">
    <img class="watermark-logo" src="<?php echo e($logo); ?>" alt="Watermark">

    
    <div class="header">
        <div class="logo">
            <img src="<?php echo e($logo); ?>" alt="Barangay Logo">
        </div>
        <div class="text">
            <h2>Republic of the Philippines</h2>
            <h3>Barangay <?php echo e($barangay->name ?? 'N/A'); ?></h3>
            <p>Municipality of Laoac, Province of Pangasinan</p>
        </div>
        <div style="width: 80px;"></div> 
    </div>

    
    <div class="title">CERTIFICATE OF RESIDENCY</div>

    
    <div class="main-section">
        
        <div class="officials-section">
            <div class="officials-title">Barangay Officials</div>
            <div class="officials-list">
                <p><strong>Barangay Captain:</strong><br>
                    <?php echo e(isset($captain) && is_object($captain)
                        ? 'HON. ' . $captain->first_name . ' ' . $captain->middle_name . ' ' . $captain->last_name
                        : '-'); ?>

                </p>

                <p><strong>Barangay Secretary:</strong><br>
                    <?php echo e(isset($secretary) && is_object($secretary)
                        ? 'HON. ' . $secretary->first_name . ' ' . $secretary->middle_name . ' ' . $secretary->last_name
                        : '-'); ?>

                </p>

                <p><strong>Kagawads:</strong></p>
                <ul>
                    <?php $__empty_1 = true; $__currentLoopData = $kagawads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kagawad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <li>
                            HON. <?php echo e($kagawad->first_name); ?> <?php echo e($kagawad->middle_name); ?> <?php echo e($kagawad->last_name); ?><?php echo e($kagawad->suffix ? ', ' . $kagawad->suffix : ''); ?>

                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <li>-</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        
        <div class="content-section">
            <div class="content">
                <p>TO WHOM IT MAY CONCERN:</p>

                <p>
                    This is to certify that Mr./Ms. <strong><?php echo e($request->resident->full_name ?? 'N/A'); ?></strong>,
                    born on <strong><?php echo e(\Carbon\Carbon::parse($request->resident->birthdate)->format('F d, Y')); ?></strong>,
                    <?php echo e($request->resident->age); ?> years old, <?php echo e(strtolower($request->resident->gender)); ?>,
                    <?php echo e(strtolower($request->resident->civil_status)); ?>,
                    and a resident of Barangay <?php echo e($barangay->name ?? 'N/A'); ?>, Laoac, Pangasinan,
                    is known to me personally and has been residing here for almost
                    <strong>
                        <?php if($request->years_of_residency || $request->months_of_residency): ?>
                            <?php echo e($request->years_of_residency ? $request->years_of_residency . ' year' . ($request->years_of_residency > 1 ? 's' : '') : ''); ?>

                            <?php echo e($request->months_of_residency ? $request->months_of_residency . ' month' . ($request->months_of_residency > 1 ? 's' : '') : ''); ?>

                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </strong>
                    as per record filed and kept in this office.
                </p>

                <p>
                    This Certificate of Residency is issued upon the request of the above-named person
                    for any legal purpose it may serve.
                </p>

                <p>
                    Given this <strong><?php echo e(now()->format('jS')); ?></strong> day of <strong><?php echo e(now()->format('F, Y')); ?></strong>
                    at Barangay <?php echo e($barangay->name ?? 'N/A'); ?>, Laoac, Pangasinan.
                </p>

                
                <div class="signature">
                    <p><strong><?php echo e($captain->full_name ?? 'ARNEL R. ICO'); ?></strong><br>
                        Punong Barangay</p>
                </div>

                
                <div class="footer">
                    <p>Barangay Receipt No.: <strong><?php echo e($request->receipt_number ?? '__________'); ?></strong></p>
                    <p>Date: <strong><?php echo e($request->pickup_date ? \Carbon\Carbon::parse($request->pickup_date)->format('M d, Y') : '__________'); ?></strong></p>
                    <p>Amount: Php <strong><?php echo e(number_format($request->price, 2) ?? '__________'); ?></strong></p>
                </div>
            </div>
        </div>
    </div>
</div> 


<div class="no-print">
    <a href="<?php echo e(route('secretary.document_requests.edit', $request->id)); ?>" class="btn btn-warning" title="Edit">✏️</a>
    <button onclick="window.print()" class="btn btn-success" title="Print">🖨️</button>
    <a href="<?php echo e(route('secretary.document_requests.show', $request->id)); ?>" class="btn btn-secondary" title="Back">⬅</a>
</div>

</body>
</html>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/documents/barangay_certificate_of_residency.blade.php ENDPATH**/ ?>