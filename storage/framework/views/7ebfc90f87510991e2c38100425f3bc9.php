<?php
    $barangay = auth()->user()->barangay;
  $municipality = $barangay ? $barangay->municipality : null;
    $defaultLogo = asset('images/logo.png');

    $barangayLogo = $barangay && $barangay->logo
        ? asset('storage/' . $barangay->logo)
        : $defaultLogo;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blotter Record - <?php echo e($blotter->id); ?></title>

    <style>
        /* Letter page layout */
        @page { size: Letter; margin: 40px; }
        body { 
            font-family: "Times New Roman", Times, serif; 
            margin: 0; padding: 0;
            color: #000;
        }
        .container { width: 100%; padding: 30px 40px; box-sizing: border-box; }

        /* Header */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .header img {
            height: 85px;
            object-fit: contain;
        }
        .header-text {
            flex: 1;
            text-align: center;
            padding: 0 15px;
        }
        .header-text h2,
        .header-text h3 {
            margin: 0;
            line-height: 1.2;
        }

        /* Title */
        h1 { 
            text-align: center; 
            margin: 25px 0 15px; 
            font-size: 22px;
            text-transform: uppercase;
        }

        /* Content */
        .content { margin-top: 25px; line-height: 1.7; font-size: 16px; }
        ul { margin: 0; padding-left: 20px; }

        .section { margin-bottom: 20px; }

        /* Footer */
        .footer {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }
        .signature-block {
            width: 45%;
        }
        .signature-name {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .print-btn { 
            margin-top: 20px; 
            padding: 10px 20px; 
            background: #007bff; 
            color: white; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer;
        }
        @media print { .print-btn { display: none; } }
    </style>
</head>

<body>
    <div class="container">

        
        <div class="header">
            <img src="<?php echo e($defaultLogo); ?>" alt="Left Logo">

            <div class="header-text">
                <h2>Republic of the Philippines</h2>
                <h3>Province of Pangasinan</h3>
                <h3>Municipality of <?php echo e($municipality->name ?? 'N/A'); ?></h3>
                <h3><strong>Barangay <?php echo e($barangayName ?? 'N/A'); ?></strong></h3>
            </div>

            
          <img src="<?php echo e($barangayLogo); ?>" alt="Barangay Logo">

        </div>

        
        <h1>Barangay Blotter Record</h1>

        
        <div class="content">

            <div class="section">
                <p><strong>Date & Time:</strong> 
                    <?php echo e(\Carbon\Carbon::parse($blotter->date.' '.$blotter->time)->format('F d, Y g:i A')); ?>

                </p>
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

        
        <div class="footer">

            
            <div class="signature-block">
                <?php $__currentLoopData = $blotter->complainants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="signature-name">
                        <?php echo e($c->first_name); ?> <?php echo e($c->middle_name ?? ''); ?> <?php echo e($c->last_name); ?>

                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <div>Complainant<?php echo e($blotter->complainants->count() > 1 ? 's' : ''); ?></div>
            </div>

            
            <div class="signature-block" style="text-align:right;">
                <div class="signature-name">
                    <?php echo e($captain->first_name); ?> <?php echo e($captain->middle_name ?? ''); ?> <?php echo e($captain->last_name); ?>

                </div>
                <div>Barangay Captain</div>
            </div>

        </div>

        
        <button onclick="window.print()" class="print-btn">Print</button>

    </div>
</body>
</html>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/blotter/print.blade.php ENDPATH**/ ?>