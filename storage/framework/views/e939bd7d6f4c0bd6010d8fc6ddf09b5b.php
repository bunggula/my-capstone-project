<?php
    $barangay = $request->resident->barangay ?? null;
    $logo = $barangay && $barangay->logo
        ? asset('storage/' . $barangay->logo)
        : asset('assets/images/logo.png');

    $formData = $request->form_data ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Electrical Clearance</title>
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
        justify-content: center;
        margin-bottom: 5px; /* binawasan */
        gap: 6px; /* mas maliit yung pagitan ng logo at text */
    }

    .header .logo {
        width: 65px;
        height: 65px;
        flex-shrink: 0;
    }

    .header .logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .header .text {
        text-align: center;
        line-height: 1.2; /* mas compact */
    }

    .title {
        font-weight: bold;
        text-align: center;
        margin: 5px 0; /* binawasan spacing */
        font-size: 16px;
        text-transform: uppercase;
    }

    .subtitle {
        text-align: center;
        font-size: 13px;
        font-weight: bold;
        margin-bottom: 10px; /* binawasan spacing */
    }
        /* Layout */
        .main-section {
            display: flex;
            margin-top: 20px;
            border-top: 1px solid #ccc;
            padding-top: 20px;
        }

        /* Officials (left side) */
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

        /* Content (right side) */
        .content-section {
            width: 65%;
            padding-left: 20px;
        }

        .content-section .content {
            text-align: justify;
            font-size: 13px;
        }

        .signature {
            margin-top: 60px;
            text-align: right;
        }

        .no-print {
            margin-top: 30px;
            text-align: center;
        }

        @media print {
            .no-print { display: none; }
            @page { margin: 15mm; }
        }
    </style>
</head>
<body onload="window.print()">

    
    <div class="header">
        <div class="logo">
            <img src="<?php echo e($logo); ?>" alt="Barangay Logo">
        </div>
        <div class="text">
            <h2>Republic of the Philippines</h2>
            <h3>Province of Pangasinan</h3>
            <h3>Municipality of Laoac</h3>
            <h3>Barangay <?php echo e($barangay->name ?? 'N/A'); ?></h3>
            <p><strong>OFFICE OF THE PUNONG BARANGAY</strong></p>
            
        </div>
    </div>

    
    <div class="title">ELECTRICAL CLEARANCE</div>
   

    
    <div class="main-section">

        
        <div class="officials-section">
            <div class="officials-title">Barangay Officials</div>
            <div class="officials-list">
                <p><strong>Barangay Captain:</strong><br>
                    <?php echo e(isset($captain) ? $captain->first_name . ' ' . $captain->middle_name . ' ' . $captain->last_name : '-'); ?>

                </p>

                <p><strong>Barangay Secretary:</strong><br>
                    <?php echo e(isset($secretary) ?  $secretary->first_name . ' ' . $secretary->middle_name . ' ' . $secretary->last_name : '-'); ?>

                </p>

                <p><strong>Kagawads:</strong></p>
                <ul>
                    <?php $__empty_1 = true; $__currentLoopData = $kagawads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kagawad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <li>
                            <?php echo e($kagawad->first_name); ?> <?php echo e($kagawad->middle_name); ?> <?php echo e($kagawad->last_name); ?><?php echo e($kagawad->suffix ? ', ' . $kagawad->suffix : ''); ?>

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
            <p>Date: <?php echo e(now()->format('F d, Y')); ?></p>
                <p>TO WHOM IT MAY CONCERN:</p>

                <p>
                    CLEARANCE IS GRANTED to Mr./Ms.
                    <strong><?php echo e($request->resident->full_name ?? 'N/A'); ?></strong>,
                    of Zone <?php echo e($request->resident->zone ?? '___'); ?> Barangay <?php echo e($barangay->name ?? 'N/A'); ?>, Laoac, Pangasinan
                    to hold their <strong><?php echo e(strtoupper($request->purpose ?? 'ELECTRICAL AND WIRING INSTALLATION/CONNECTION')); ?></strong>
                    from <strong><?php echo e(\Carbon\Carbon::parse($formData['start_date'] ?? now())->format('F d, Y')); ?></strong>
                    to <strong><?php echo e(\Carbon\Carbon::parse($formData['end_date'] ?? now())->format('F d, Y')); ?></strong>
                    at Barangay <?php echo e($barangay->name ?? 'N/A'); ?>, Laoac, Pangasinan subject to all existing decrees, laws and ordinances governing the same.
                </p>

                <p>
                    Given this <?php echo e(now()->format('jS')); ?> day of <?php echo e(now()->format('F, Y')); ?>

                    at Barangay <?php echo e($barangay->name ?? 'N/A'); ?>, Laoac, Pangasinan.
                </p>
                <div class="signature">
                <p><strong><?php echo e($request->resident->full_name ?? 'N/A'); ?></strong><br>
                Signature Over Printed name</p>
            </div>
                
                <div class="signature">
                    <p><strong><?php echo e($captain->full_name ?? '____________________'); ?></strong><br>
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
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/documents/electrical_clearance.blade.php ENDPATH**/ ?>