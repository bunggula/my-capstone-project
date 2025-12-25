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
            margin-bottom: 30px; /* Increased spacing after header */
        }

        .header .logo {
            width: 90px;
            height: 90px;
            margin-right: 15px; /* Reduced space between logo and text */
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

        .header .text h2,
        .header .text h3 {
            margin: 0;
            line-height: 1.3;
        }

        .header .text p {
            margin: 4px 0 0;
        }

        .title {
            font-weight: bold;
            text-align: center;
            margin: 25px 0 15px;
            font-size: 15px;
            text-transform: uppercase;
        }

        .main-section {
            display: flex;
            margin-top: 10px; /* Less space between header and main content */
            border-top: 1px solid #ccc;
            padding-top: 25px;
        }

        .officials-section {
            width: 32%;
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
            padding-left: 18px;
        }

        .content-section {
            width: 68%;
            padding-left: 25px;
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
            <h3>Province of Pangasinan</h3>
            <h3>Municipality of Laoac</h3>
            <h3><strong>Barangay <?php echo e($barangay->name ?? 'N/A'); ?></strong></h3>
            <p><strong>BAGONG PILIPINAS</strong></p>
        </div>
        <div style="width: 90px;"></div>
    </div>

    
    <div class="main-section">
        
        <div class="officials-section">
            <div class="officials-title">Barangay Officials</div>
            <div class="officials-list">
                <p><strong>Punong Barangay:</strong><br>
                    <?php echo e(isset($captain) ? 'HON. ' . $captain->full_name : '-'); ?>

                </p>

                <p><strong>Barangay Secretary:</strong><br>
                    <?php echo e(isset($secretary) ? 'HON. ' . $secretary->full_name : '-'); ?>

                </p>

                <p><strong>Kagawads:</strong></p>
                <ul>
                    <?php $__empty_1 = true; $__currentLoopData = $kagawads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kagawad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <li>HON.<?php echo e($kagawad->first_name); ?> <?php echo e($kagawad->middle_name); ?> <?php echo e($kagawad->last_name); ?></li>
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
                
                <div class="title">Barangay Business Clearance</div>

                
                <p><strong>Date:</strong> <?php echo e(\Carbon\Carbon::parse($request->pickup_date)->format('F d, Y')); ?></p>

                <p><strong>TO WHOM IT MAY CONCERN:</strong></p>

                <p>
                    CLEARANCE IS GRANTED to <strong><?php echo e($request->resident->full_name ?? 'N/A'); ?></strong>,
                    of Barangay  <?php echo e($barangay->name ?? '__________'); ?>, Laoac, Pangasinan,,
                    to hold their BUSINESS TO OPERATE <strong>(<?php echo e(strtoupper($request->form_data['business_name'] ?? '__________')); ?>)</strong>
                    from <?php echo e(\Carbon\Carbon::parse($request->form_data['start_date'] ?? now())->format('F d, Y')); ?>

                    to <strong><?php echo e(\Carbon\Carbon::parse($request->form_data['end_date'] ?? now())->format('F d, Y')); ?></strong> 
                    at Barangay <?php echo e($barangay->name ?? '__________'); ?>, Laoac, Pangasinan,
                    subject to all existing decrees, laws, and ordinances governing the same.
                </p>

                <p>
                    Given this <?php echo e(\Carbon\Carbon::parse($request->pickup_date)->format('jS')); ?> day of
                    <?php echo e(\Carbon\Carbon::parse($request->pickup_date)->format('F, Y')); ?>

                    at Barangay <?php echo e($barangay->name ?? ''); ?>, Laoac, Pangasinan.
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
       
        <button onclick="window.print()" class="btn btn-success" title="Print">🖨️</button>
        
    </div>

</body>
</html>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/documents/business_clearance.blade.php ENDPATH**/ ?>