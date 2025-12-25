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
    <title>Barangay Clearance</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            padding: 40px;
            font-size: 13px;
            line-height: 1.6;
            background: white;
        }

        /* Header */
        .header {
            display: flex;
            align-items: center;
            justify-content: center; /* center everything horizontally */
            gap: 10px; /* smaller gap between logo and text */
            margin-bottom: 10px;
        }

        .header .logo {
            width: 60px;
            height: 60px;
            flex-shrink: 0;
        }

        .header .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .header .text {
            text-align: center;
            line-height: 1.1;
        }

        /* Title */
        .title {
            font-weight: bold;
            text-align: center;
            margin: 15px 0 10px;
            font-size: 15px;
            text-transform: uppercase;
            position: relative;
        }

      
        

        /* Main content */
        .main-section {
            display: flex;
            margin-top: 20px;
            border-top: 1px solid #ccc;
            padding-top: 20px;
        }

        /* Officials */
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

        /* Letter content */
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
            text-align: center;
        }

        .no-print {
            margin-top: 30px;
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
        Barangay Clearance
    </div>

    
    <div class="main-section">
        
        <div class="officials-section">
            <div class="officials-title">Barangay Officials</div>
            <div class="officials-list">
                <p><strong>Barangay Captain:</strong><br>
                    <?php echo e(isset($captain) ? 'HON. ' . $captain->first_name . ' ' . $captain->middle_name . ' ' . $captain->last_name : '-'); ?>

                </p>

                <p><strong>Barangay Secretary:</strong><br>
                    <?php echo e(isset($secretary) ? 'HON. ' . $secretary->first_name . ' ' . $secretary->middle_name . ' ' . $secretary->last_name : '-'); ?>

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
                <p><strong>SK Chairman:</strong><br>
    <?php echo e(isset($skChairman) ?   $skChairman->first_name . ' ' . $skChairman->middle_name . ' ' . $skChairman->last_name : '-'); ?>

</p>
            </div>
        </div>

        
        <div class="content-section">
            <div class="content">
                <p>TO WHOM IT MAY CONCERN:</p>

                <p>
                    This is to certify that <strong><?php echo e($request->resident->full_name ?? 'N/A'); ?></strong>,
                    Filipino, legal age, <?php echo e($request->resident->age); ?>,
                    and a resident of Barangay <?php echo e($barangay->name ?? 'N/A'); ?>, Laoac, Pangasinan,
                    is known to me personally as a person of good moral character, honest, trustworthy,
                    law-abiding citizen and has no derogatory/criminal liability record from the date thereof.
                </p>

                <p>
                    This CERTIFICATION is being issued upon the request of the above-named person
                    for <strong><?php echo e($request->purpose ?? 'CLEARANCE AND IDENTIFICATION'); ?></strong>.
                </p>

                <p>
                    Given this <?php echo e(now()->format('jS')); ?> day of <?php echo e(now()->format('F, Y')); ?> at Barangay <?php echo e($barangay->name ?? ''); ?>, Laoac, Pangasinan.
                </p>
                <div class="signature">
                <p><strong><?php echo e($request->resident->full_name ?? 'N/A'); ?></strong><br>
                Signature Over Printed name</p>
            </div>
                
                <div class="signature">
                    <p><strong><?php echo e($captain->full_name ?? ''); ?></strong><br>
                    Punong Barangay</p>
                </div>
            </div>
        </div>
    </div>

    
    <div class="no-print">
        <button onclick="window.print()" class="btn btn-success" title="Print">
            🖨️ Print
        </button>
    </div>

</body>
</html>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/documents/clearance.blade.php ENDPATH**/ ?>