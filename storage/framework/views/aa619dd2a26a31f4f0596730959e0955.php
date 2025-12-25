<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Proposal</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
    <style>
        @page {
            size: letter;
            margin: 1in; /* standard letter margin */
        }
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 0; 
        }
        .container {
            padding: 40px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .indent-8 { text-indent: 2rem; }
        .text-justify { text-align: justify; }
        img { max-height: 80px; display:block; margin: 0 auto; }
        a { color: #1d4ed8; text-decoration: underline; }
        .header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .header-center { flex: 1; text-align: center; }
    </style>
</head>
<body onload="window.print()">
    <div class="container">
        
        <div class="header-flex">
            
            <?php if($proposal->barangayInfo?->municipality?->logo): ?>
                <img src="<?php echo e(asset('storage/'.$proposal->barangayInfo->municipality->logo)); ?>" alt="Municipality Logo" class="h-16 w-16 object-contain">
            <?php else: ?>
                <div style="width:80px;"></div> 
            <?php endif; ?>

            
            <div class="header-center">
                <p class="text-xs uppercase tracking-widest">Republika ng Pilipinas</p>
                <p class="text-sm">Municipality of <?php echo e($proposal->barangayInfo?->municipality?->name ?? 'N/A'); ?></p>
                <p class="text-sm font-semibold">Barangay <?php echo e($proposal->barangayInfo?->name ?? 'N/A'); ?></p>
            </div>

            
            <?php if($proposal->barangayInfo?->logo): ?>
                <img src="<?php echo e(asset('storage/'.$proposal->barangayInfo->logo)); ?>" alt="Barangay Logo" class="h-16 w-16 object-contain">
            <?php else: ?>
                <div style="width:80px;"></div> 
            <?php endif; ?>
        </div>

        
        <div class="text-sm">
            <p class="text-right"><?php echo e(now()->format('F d, Y')); ?></p>
            <p class="mt-8">To: <strong>Office of ABC President</strong></p>
            <p>From: Barangay <?php echo e($proposal->barangayInfo?->name ?? 'N/A'); ?></p>
            <p>Barangay Captain: <strong><?php echo e(optional($proposal->captain)->full_name ?? 'Unknown'); ?></strong></p>

            <p class="mt-6">Dear Sir/Madam,</p>

            <p class="mt-4 text-justify indent-8">
                We, the officials of Barangay <?php echo e($proposal->barangayInfo?->name ?? 'N/A'); ?>, respectfully submit this project proposal entitled 
                <strong><?php echo e($proposal->title); ?></strong>
                <?php if($proposal->target_date): ?>
                    with a target completion date of <strong><?php echo e(\Carbon\Carbon::parse($proposal->target_date)->format('F d, Y')); ?></strong>
                <?php endif; ?>
                . This initiative is part of our continuous effort to improve the quality of life of our constituents and to address key concerns in our community.
            </p>

            <p class="mt-4 text-justify indent-8"><?php echo e($proposal->description); ?></p>

            <?php if($proposal->file): ?>
                <p class="mt-5">
                    📎 <a href="<?php echo e(asset('storage/'.$proposal->file)); ?>" target="_blank">View Attached Document</a>
                </p>
            <?php endif; ?>

            <p class="mt-6">Thank you for your consideration. We look forward to your support and favorable response.</p>
            <p class="mt-6">Respectfully yours,</p>

            <div class="mt-8">
                <p><strong><?php echo e(optional($proposal->captain)->full_name ?? 'Barangay Captain'); ?></strong></p>
                <p>Barangay Captain</p>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/captain/proposal/print.blade.php ENDPATH**/ ?>