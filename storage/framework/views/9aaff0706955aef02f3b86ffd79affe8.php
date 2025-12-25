<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
    body {
        font-family: 'Segoe UI', sans-serif;
        padding: 30px 40px;
        font-size: 13px;
        line-height: 1.5;
        position: relative;
        background: white;
    }

    .header {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-bottom: 10px;
    }

    .header img {
        width: 55px;
        height: 55px;
        object-fit: contain;
    }

    .header-text {
        text-align: center;
    }

    .header-text h2 {
        margin: 0;
        font-size: 16px;
        text-transform: uppercase;
    }

    .header-text p {
        margin: 0;
        font-size: 11px;
    }

    .title {
        text-align: center;
        font-size: 15px;
        font-weight: bold;
        margin: 20px 0 10px;
        text-transform: uppercase;
    }

    .main-section {
        display: flex;
        margin-top: 20px;
        border-top: 1px solid #ccc;
        padding-top: 15px;
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
        text-align: left;
        text-transform: uppercase;
    }

    .officials-list {
        font-size: 13px;
    }

    .officials-list p,
    .officials-list li {
        margin: 3px 0;
    }

    .officials-list ul {
        padding-left: 20px;
        margin-top: 5px;
    }

    .content-section {
        width: 65%;
        padding-left: 20px;
    }

    .content-section .content {
        font-size: 13px;
        white-space: pre-wrap;
        text-align: justify;
    }

    .watermark {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        opacity: 0.05;
        width: 250px;
        z-index: 0;
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

        body {
            background: white;
        }
    }
    </style>
</head>
<body>

    
    <?php
        $barangay = $request->resident->barangay ?? null;
        $logo = $barangay && $barangay->logo ? asset('storage/' . $barangay->logo) : asset('assets/images/logo.png');
    ?>

    <img src="<?php echo e($logo); ?>" alt="Watermark" class="watermark">

    
    <div class="header">
        <img src="<?php echo e($logo); ?>" alt="Barangay Logo">
        <div class="header-text">
            <h2>Barangay <?php echo e($barangay->name ?? 'N/A'); ?></h2>
            <p>Municipality / City</p>
            <p>Province</p>
        </div>
    </div>

    <hr>

    
    <div class="title">
        <?php echo e($request->title); ?>

    </div>

    
    <div class="main-section">
        
        <div class="officials-section">
            <div class="officials-title">Barangay Officials</div>
            <div class="officials-list">
                <p><strong>Barangay Captain:</strong><br>
                    <?php echo e(isset($captain) && is_object($captain)
                        ?'HON.'. $captain->first_name . ' ' . $captain->middle_name . ' ' . $captain->last_name
                        : '-'); ?>

                </p>

                <p><strong>Barangay Secretary:</strong><br>
                    <?php echo e(isset($secretary) && is_object($secretary)
                        ? 'HON.'.$secretary->first_name . ' ' . $secretary->middle_name . ' ' . $secretary->last_name
                        : '-'); ?>

                </p>

                <p><strong>Kagawads:</strong></p>
                <ul>
                    <?php $__empty_1 = true; $__currentLoopData = $kagawads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kagawad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <li>HON.<?php echo e($kagawad->first_name); ?> <?php echo e($kagawad->middle_name); ?> <?php echo e($kagawad->last_name); ?><?php echo e($kagawad->suffix ? ', ' . $kagawad->suffix : ''); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <li>-</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        
        <div class="content-section">
            <div class="content">
                <?php echo nl2br(e($request->content)); ?>

            </div>
        </div>
    </div>

    
    <div class="no-print">
        <button onclick="window.print()">🖨️ I-print ang Dokumento</button>
        <a href="<?php echo e(route('secretary.document_requests.show', $request->id)); ?>">⬅ Bumalik</a>
    </div>

</body>
</html>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/document_requests/print.blade.php ENDPATH**/ ?>