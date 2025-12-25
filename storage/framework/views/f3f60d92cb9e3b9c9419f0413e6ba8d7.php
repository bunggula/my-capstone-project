<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blotter Record - <?php echo e($blotter->id); ?></title>
    <style>
        /* Letter page */
        @page { size: Letter; margin: 40px; }
        body { font-family: 'Times New Roman', Times, serif; margin: 0; padding: 0; }
        .container { width: 100%; padding: 40px; box-sizing: border-box; }

        /* Header */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .header img {
            height: 80px;
            margin: 0; /* remove extra spacing */
        }
        .header-text {
            text-align: center;
            flex: 1;
            margin: 0 10px; /* small spacing from logos */
        }
        .header-text h2, .header-text h3 { margin: 0; }

        /* Title */
        h1 { text-align: center; margin-top: 20px;  }

        /* Content */
        .content { margin-top: 30px; line-height: 1.6; }
        ul { margin: 0 0 0 20px; padding: 0; }
        .section { margin-bottom: 20px; }

        /* Footer for Captain signature */
        .footer { margin-top: 60px; text-align: right; }
        .footer .name { font-weight: bold; }
        .footer .position { margin-top: 5px; }

        /* Print button */
        .print-btn { display: inline-block; margin-top: 20px; padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        @media print { .print-btn { display: none; } }
    </style>
</head>
<body>
    <div class="container">
        
        <div class="header">
            <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Barangay Logo">
            <div class="header-text">
                <h2>Republic of the Philippines</h2>
                <h3>Province of Pangasinan</h3>
                <h3>Municipality of Laoac</h3>
                <h3><strong>Barangay <?php echo e($barangayName ?? 'N/A'); ?></strong></h3>
            </div>
            <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Logo" style="opacity:0;">
        </div>

        
        <h1>Barangay Blotter Record</h1>

        
        <div class="content">
            <div class="section">
                <p><strong>Date & Time:</strong> <?php echo e(\Carbon\Carbon::parse($blotter->date.' '.$blotter->time)->format('F d Y g:i A')); ?></p>
            </div>

            <div class="section">
                <p><strong>Complainants:</strong></p>
                <ul>
                    <?php $__currentLoopData = $blotter->complainants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($c->first_name); ?> <?php echo e($c->middle_name ?? ''); ?> <?php echo e($c->last_name); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>

            <div class="section">
                <p><strong>Respondents:</strong></p>
                <ul>
                    <?php $__currentLoopData = $blotter->respondents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($r->first_name); ?> <?php echo e($r->middle_name ?? ''); ?> <?php echo e($r->last_name); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>

            <div class="section">
                <p><strong>Description:</strong></p>
                <p><?php echo e($blotter->description); ?></p>
            </div>
        </div>

        
        
        <div class="footer" style="display: flex; justify-content: space-between; margin-top: 60px;">
    
    <div class="complainants" style="text-align: left;">
        <?php $__currentLoopData = $blotter->complainants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="name" style="font-weight: bold; margin-bottom: 5px;">
                <?php echo e($c->first_name); ?> <?php echo e($c->middle_name ?? ''); ?> <?php echo e($c->last_name); ?>

            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <div class="position" style="margin-top: 10px;">Complainant<?php echo e($blotter->complainants->count() > 1 ? 's' : ''); ?></div>
    </div>

    
    <div class="captain" style="text-align: right;">
        <div class="name" style="font-weight: bold;  margin-bottom: 5px;">
            <?php echo e($captain->first_name); ?> <?php echo e($captain->middle_name ?? ''); ?> <?php echo e($captain->last_name); ?>

        </div>
        <div class="position" style="margin-top: 10px;">Barangay Captain</div>
    </div>
</div>





        
        <button onclick="window.print()" class="print-btn">Print</button>
    </div>
</body>
</html>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/captain/blotter/print.blade.php ENDPATH**/ ?>