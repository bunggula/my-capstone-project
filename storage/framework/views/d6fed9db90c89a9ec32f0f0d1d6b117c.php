<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Residents Print</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        h2, h3, h4, p { margin: 2px 0; }
        h2.title { margin-top: 20px; text-align: center; text-transform: uppercase; }

        .header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            line-height: 1.4;
            margin-bottom: 20px;
        }

        .header img {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }

        .header-text {
            text-align: center;
        }

        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
        }
        th, td { 
            border: 1px solid #999; 
            padding: 8px; 
            text-align: left; 
            font-size: 14px; 
        }
        th { background-color: rgb(173, 176, 180); color: white; }
        tr:nth-child(even) { background: #f2f2f2; }

        td.name, td.bday { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .info { 
            text-align: left; 
            font-size: 14px; 
            color: #555; 
            margin-top: 10px; 
            font-weight: 600;
        }

        .footer {
            margin-top: 40px;
            font-size: 14px;
            color: #333;
        }

        .footer .prepared {
            margin-top: 60px;
            text-align: left;
        }

        @media print {
            #print-btn { display: none !important; }
            body { margin: 0; }
        }
    </style>
</head>
<body>


<div class="header">
    <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Barangay Logo">
    <div class="header-text">
        <h2>Republic of the Philippines</h2>
        <h3>Province of Pangasinan</h3>
        <h3>Municipality of Laoac</h3>
        <h3><strong>Barangay <?php echo e($barangayName ?? 'N/A'); ?></strong></h3>
    </div>
    <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Barangay Logo" style="opacity:0;"> 
</div>

<?php
    $selectedCategory = $category;
    $selectedVoter = $voter;

    // Map categories and voter
    $categoryLabels = [
        'PWD' => 'Person With Disability',
        'Senior' => 'Senior Citizen',
        'Indigenous' => 'Indigenous People',
        'Single Parent' => 'Single Parent',
        'Minor' => 'Minor (Under 18)',
        'Adult' => 'Adult (18+)',
    ];
    $voterLabels = ['Yes' => 'Registered', 'No' => 'Non-Registered'];

    $filters = [];
    if($selectedCategory) $filters[] = $categoryLabels[$selectedCategory] ?? $selectedCategory;
    if($selectedVoter) $filters[] = $voterLabels[$selectedVoter] ?? $selectedVoter;
    $filtersText = count($filters) ? implode(' | ', $filters) : 'All Residents';
?>



<p class="info">
    List of Residents of Barangay <?php echo e($barangayName ?? 'All Barangays'); ?><?php echo e(count($filters) ? ' | ' . $filtersText : ''); ?>

</p>


<a href="#" id="print-btn"
   onclick="window.print()"
   style="display:inline-block;background:#16a34a;color:#fff;padding:8px 16px;border-radius:6px;text-decoration:none;margin-top:10px;">
    Print Page
</a>


<?php if($residents->count()): ?>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Birthdate</th>
                <th>Barangay</th>
                <th>Category</th>
                <th>Voter</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $residents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $resident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td class="name"><?php echo e($resident->first_name); ?> <?php echo e($resident->middle_name); ?> <?php echo e($resident->last_name); ?> <?php echo e($resident->suffix); ?></td>
                    <td><?php echo e(ucfirst($resident->gender)); ?></td>
                    <td class="bday"><?php echo e(\Carbon\Carbon::parse($resident->birthdate)->format('M d, Y')); ?></td>
                    <td><?php echo e($resident->barangay->name ?? 'N/A'); ?></td>
                    <td><?php echo e($resident->category ?? 'N/A'); ?></td>
                    <td><?php echo e($resident->voter == 'Yes' ? 'Registered' : 'Non-Registered'); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    
    <div class="footer">
        <p style="text-align:right;"><strong>Total Residents:</strong> <?php echo e($residents->count()); ?></p>
        <p style="text-align:right;"><strong>Printed on:</strong> <?php echo e(now()->format('F d, Y h:i A')); ?></p>

        <?php
            $user = auth()->user();
            $fullName = trim("{$user->first_name} {$user->middle_name} {$user->last_name}");
        ?>
        <div class="prepared" style="margin-top:60px;">
            <p><strong>Prepared by:</strong> <?php echo e($fullName ?: '__________________'); ?></p>
            <p>Barangay Captain</p>
        </div>
    </div>
<?php else: ?>
    <p style="text-align:center;color:#777;margin-top:40px;">No residents found for this filter.</p>
<?php endif; ?>

</body>
</html>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/captain/residents/print.blade.php ENDPATH**/ ?>