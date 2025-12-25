<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Barangay Officials</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        h2 { margin-bottom: 10px; }
        .header { display: flex; align-items: center; justify-content: center; margin-bottom: 20px; }
        .header img { height: 80px; margin-right: 20px; }
        .header-text { text-align: center; }
        .header-text h1, .header-text h2, .header-text h3 { margin: 0; }
        .header-text h1 { font-size: 20px; font-weight: bold; }
        .header-text h2 { font-size: 18px; font-weight: bold; }
        .header-text h3 { font-size: 16px; }
    </style>
</head>
<body>

  <!-- Header with Logo -->
<div class="header">
    <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Logo">
    <div class="header-text">
        <h1>Republika ng Pilipinas</h1>
        <h2>Municipality of Laoac</h2>
        <h3>Barangay Council of <?php echo e($barangayName ?? 'All'); ?></h3>
    </div>
</div>


    <!-- Filter / Position Info -->
    <?php if($positionFilter): ?>
        <p><strong>Position:</strong> <?php echo e($positionFilter); ?></p>
    <?php endif; ?>

    <!-- Officials Table -->
    <table>
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Position</th>
                <th>Start Year</th>
                <th>End Year</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $barangays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barangay): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                
                <?php $captain = $barangay->users->firstWhere('role', 'brgy_captain'); ?>
                <?php if($captain): ?>
                    <tr>
                        <td><?php echo e($captain->last_name); ?>, <?php echo e($captain->first_name); ?> <?php echo e($captain->middle_name ?? ''); ?></td>
                        <td>Barangay Captain</td>
                        <td><?php echo e($captain->start_year ?? '-'); ?></td>
                        <td><?php echo e($captain->end_year ?? '-'); ?></td>
                    </tr>
                <?php endif; ?>

                
                <?php $secretary = $barangay->users->firstWhere('role', 'secretary'); ?>
                <?php if($secretary): ?>
                    <tr>
                        <td><?php echo e($secretary->last_name); ?>, <?php echo e($secretary->first_name); ?> <?php echo e($secretary->middle_name ?? ''); ?></td>
                        <td>Barangay Secretary</td>
                        <td><?php echo e($secretary->start_year ?? '-'); ?></td>
                        <td><?php echo e($secretary->end_year ?? '-'); ?></td>
                    </tr>
                <?php endif; ?>

                
                <?php $__currentLoopData = $barangay->barangayOfficials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $official): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($official->last_name); ?>, <?php echo e($official->first_name); ?> <?php echo e($official->middle_name ?? ''); ?></td>
                        <td><?php echo e($official->position); ?></td>
                        <td><?php echo e($official->start_year ?? '-'); ?></td>
                        <td><?php echo e($official->end_year ?? '-'); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

</body>
</html>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/abc/accounts/print.blade.php ENDPATH**/ ?>