<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Document Rejected</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f7f7f7; padding: 20px;">

    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
        
        <h2 style="color: #c62828;">📄 Hello <?php echo e($request->resident->first_name); ?> <?php echo e($request->resident->last_name); ?>,</h2>

        <p style="font-size: 16px;">
            We're sorry to inform you that your document request for 
            <strong><?php echo e($request->document_type); ?></strong> has been 
            <span style="color: red;"><strong>rejected</strong></span>.
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
        </table>

        <?php if($request->rejection_reason): ?>
        <div style="margin-top: 20px; background-color: #fff3f3; border-left: 4px solid #e53935; padding: 10px 15px; border-radius: 5px;">
            <strong>📢 Reason for Rejection:</strong><br>
            <em><?php echo e($request->rejection_reason); ?></em>
        </div>
        <?php endif; ?>

        <p style="margin-top: 30px;">If you have any questions, please contact your barangay office for clarification.</p>

        <p style="font-size: 15px;">
            Rejected by:<br>
            <strong><?php echo e($secretary->first_name); ?> <?php echo e($secretary->last_name); ?></strong><br>
            <em>Barangay Secretary</em>
        </p>

    </div>

</body>
</html>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/emails/document_rejected.blade.php ENDPATH**/ ?>