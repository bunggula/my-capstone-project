<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Barangay Residents PDF</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #999; padding: 6px; text-align: left; }
        th { background-color: #eee; }
    </style>
</head>
<body>

    <h2>Residents of Barangay <?php echo e(Auth::user()->barangay->name ?? 'N/A'); ?></h2>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Gender</th>
                <th>Birthdate</th>
                <th>Age</th>
                <th>Civil Status</th>
                <th>Category</th>
                <th>Phone</th>
                <th>Address</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $residents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($resident->first_name); ?> <?php echo e($resident->middle_name); ?> <?php echo e($resident->last_name); ?> <?php echo e($resident->suffix); ?></td>
                <td><?php echo e($resident->gender); ?></td>
                <td><?php echo e($resident->birthdate); ?></td>
                <td><?php echo e($resident->age); ?></td>
                <td><?php echo e($resident->civil_status); ?></td>
                <td><?php echo e($resident->category ?? 'N/A'); ?></td>
                <td><?php echo e($resident->phone ?? 'N/A'); ?></td>
                <td><?php echo e($resident->address); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

</body>
</html>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/exports/captain-residents-pdf.blade.php ENDPATH**/ ?>