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

        /* Header */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .header .logo {
            width: 80px;
            height: 80px;
            margin-right: 10px;
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
            line-height: 1.2;
        }

        .header .text p {
            margin: 4px 0 0;
        }

     

        /* Title */
        .title {
            font-weight: bold;
            text-align: center;
            margin: 10px 0 10px;
            font-size: 16px;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }

        /* Main layout */
        .main-section {
            display: flex;
            margin-top: 20px;
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
            text-align: left;
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
            <p><strong>OFFICE OF THE PUNONG BARANGAY</strong></p>
        </div>
        <div style="width: 80px;"></div>
    </div>

    
    <div class="header-line"></div>

    
    <div class="title">CERTIFICATION</div>

    
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
                        <li>HON. <?php echo e($kagawad->first_name); ?> <?php echo e($kagawad->middle_name); ?> <?php echo e($kagawad->last_name); ?></li>
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
                <p><strong>TO WHOM IT MAY CONCERN:</strong></p>

                <p>
                    This is to certify that <strong><?php echo e($request->resident->full_name ?? '__________'); ?></strong> 
                    is a bonafide resident of Barangay <strong><?php echo e($barangay->name ?? '__________'); ?></strong>, 
                    Laoac, Pangasinan.
                </p>

                <p>
                    This certification is issued upon the request of <strong>Mr./Ms. <?php echo e($request->resident->full_name ?? '__________'); ?></strong> 
                    for whatever legal purpose it may serve.
                </p>

                <p>
                    Given this <?php echo e(\Carbon\Carbon::parse($request->pickup_date)->format('jS')); ?> day of 
                    <?php echo e(\Carbon\Carbon::parse($request->pickup_date)->format('F Y')); ?>, at Barangay 
                    <?php echo e($barangay->name ?? '__________'); ?>, Laoac, Pangasinan.
                </p>
                <div class="signature">
                <p><strong><?php echo e($request->resident->full_name ?? 'N/A'); ?></strong><br>
                Signature Over Printed name</p>
            </div>
                
                <div class="signature">
                    <p><strong><?php echo e($captain->full_name ?? 'MARISSA R. CELINO'); ?></strong><br>
                    Punong Barangay</p>
                </div>
            </div>
        </div>
    </div>

    
    <div class="no-print">
        <button onclick="window.print()" class="btn btn-success">🖨️ Print</button>
    </div>

</body>
</html>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/documents/certification.blade.php ENDPATH**/ ?>