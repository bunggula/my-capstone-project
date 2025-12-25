<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Document Approved</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f7f7f7; padding: 20px;">

    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
        
        <h2 style="color: #2e7d32;">📄 Hello <?php echo e($request->resident->first_name); ?> <?php echo e($request->resident->last_name); ?>,</h2>

        <p style="font-size: 16px;">
            Your document request for 
            <strong><?php echo e($request->document_type); ?></strong> has been 
            <span style="color: green;"><strong>approved</strong></span>.
        </p>

        <table style="width: 100%; margin-top: 20px; font-size: 15px;">
            <tr>
                <td style="padding: 6px 0;"><strong>📌 Reference Code:</strong></td>
                <td><?php echo e($request->reference_code); ?></td>
            </tr>
            <tr>
                <td style="padding: 6px 0;"><strong>📋 Purpose:</strong></td>
                <td><?php echo e($request->purpose ?: 'N/A'); ?></td>
            </tr>
            <tr>
                <td style="padding: 6px 0;"><strong>📅 Pickup Date:</strong></td>
                <td><?php echo e(\Carbon\Carbon::parse($request->pickup_date)->format('F d, Y')); ?></td>
            </tr>
        </table>


        <p style="margin-top: 30px;">Thank you!</p>

        <p style="font-size: 15px;">
            Approved by:<br>
            <strong><?php echo e($secretary->first_name); ?> <?php echo e($secretary->last_name); ?></strong><br>
            <em>Barangay Secretary</em>
        </p>

    </div>

</body>
</html>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/emails/document-approved.blade.php ENDPATH**/ ?>