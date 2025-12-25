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
    <title>Certificate of Indigency</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            padding: 40px;
            font-size: 13px;
            background: white;
        }

        /* Header */
        .header {
            display: flex;
            align-items: center;
            justify-content: center; /* center everything horizontally */
            gap: 10px; /* smaller gap between logo and text */
            margin-bottom: 15px;
        }

        .logo {
            width: 60px;      /* slightly smaller logo */
            height: 60px;
            flex-shrink: 0;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .text {
            text-align: center;
            line-height: 1.1;
        }

        /* Title */
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin: 20px 0 10px;
            position: relative;
        }

        /* Line under title */
        .title::after {
            content: '';
            display: block;
            width: 100%;
            height: 2px;
            background: black;
            margin-top: 8px;
        }

        /* Content: Officials + Letter */
        .content-container {
            display: flex;
            gap: 40px;
            margin-top: 30px;
        }

        .officials-section {
            width: 30%;
            line-height: 1.6;
            padding-right: 20px;
            border-right: 2px solid #000;
        }

        .officials-section p, .officials-section ul {
            margin: 5px 0;
        }

        .officials-section ul {
            padding-left: 20px;
        }

        .letter-section {
            width: 70%;
            text-align: justify;
            line-height: 1.8;
            padding-left: 20px;
        }

        .signature {
            margin-top: 50px;
            text-align: right;
        }

        .footer {
            margin-top: 40px;
            font-size: 12px;
            text-align: center;
        }

        .no-print {
            margin-top: 20px;
            text-align: center;
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

    
    <div class="header">
        <div class="logo">
            <img src="<?php echo e($logo); ?>" alt="Barangay Logo">
        </div>
        <div class="text">
            <h2>Republic of the Philippines</h2>
            <h3>Barangay <?php echo e($barangay->name ?? 'N/A'); ?></h3>
            <p>Municipality of Laoac, Province of Pangasinan</p>
        </div>
    </div>

    
    <div class="title">
        <u>CERTIFICATE OF INDIGENCY</u>
    </div>

    
    <div class="content-container">
        
        <div class="officials-section">
            <p><strong>Punong Barangay:</strong><br>
                <?php echo e(isset($captain) ? 'HON. ' . $captain->full_name : '-'); ?>

            </p>

            <p><strong>Barangay Secretary:</strong><br>
                <?php echo e(isset($secretary) ? 'HON. ' . $secretary->full_name : '-'); ?>

            </p>

            <p><strong>Kagawads:</strong></p>
            <ul>
                <?php $__empty_1 = true; $__currentLoopData = $kagawads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kagawad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <li>HON. <?php echo e($kagawad->first_name); ?> <?php echo e($kagawad->middle_name); ?> <?php echo e($kagawad->last_name); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <li>-</li>
                <?php endif; ?>
            </ul>
        </div>

        
        <div class="letter-section">
            <p>TO WHOM IT MAY CONCERN:</p>

            <p>
                This is to certify that Mr./Ms. <strong><?php echo e($request->resident->full_name ?? 'N/A'); ?></strong>,
                of legal age, <strong><?php echo e(strtolower($request->resident->civil_status)); ?></strong>,
                a resident and living in Barangay <?php echo e($barangay->name ?? 'N/A'); ?>, Laoac, Pangasinan,
                belongs to the list of identified indigent families of this barangay and is deserving of
                any assistance from any government office or institution.
            </p>

            <p>
                This Certificate of Indigency is being issued upon the request of the said person
                for whatever legal purpose it may serve.
            </p>

            <p>
                Given this <strong><?php echo e(now()->format('jS')); ?></strong> day of <strong><?php echo e(now()->format('F, Y')); ?></strong>,
                at Barangay <?php echo e($barangay->name ?? 'N/A'); ?>, Laoac, Pangasinan.
            </p>
            <div class="signature">
                <p><strong><?php echo e($request->resident->full_name ?? 'N/A'); ?></strong><br>
                Signature Over Printed name</p>
            </div>
            <div class="signature">
                <p><strong><?php echo e($captain->full_name ?? 'TITO M. PIL'); ?></strong><br>
                Punong Barangay</p>
            </div>
        </div>
    </div>

    
    <div class="no-print">
        <button onclick="window.print()" class="btn btn-success" title="Print">🖨️ Print</button>
    </div>

</body>
</html>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/documents/barangaya_certificate_of_indigency.blade.php ENDPATH**/ ?>