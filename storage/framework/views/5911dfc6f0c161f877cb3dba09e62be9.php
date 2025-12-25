<!DOCTYPE html>
<html>
<head>
    <title>Registration Request Rejected</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: auto;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fafafa;
        }
        a.button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 15px;
            background-color: #ef4444; /* red color */
            color: white !important;
            text-decoration: none;
            border-radius: 5px;
        }
        .footer {
            margin-top: 30px;
            font-size: 0.9rem;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Dear <?php echo e($resident->first_name); ?> <?php echo e($resident->last_name); ?>,</h2>

        <p>We regret to inform you that your registration request has been <strong>rejected</strong>.</p>

<?php if(isset($reason)): ?>
    <p><strong>Reason:</strong> <?php echo e($reason); ?></p>
<?php endif; ?>

<p>You may re-submit your registration with corrected information or additional documents if necessary.</p>

<p>If you believe this was a mistake, please visit your <strong>Barangay <?php echo e($resident->barangay->name ?? 'office'); ?></strong> to resolve the issue or contact them directly for clarification.</p>




        <p>Sincerely,<br>
        Barangay Secretary's Office</p>

        <?php if(isset($approver)): ?>
    <p><em>Rejected by: <?php echo e($approver->full_name ?? $approver->name ?? 'Barangay Secretary'); ?></em></p>
<?php endif; ?>



        <div class="footer">
            <p>This is an automated message. Please do not reply directly to this email.</p>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/emails/rejected.blade.php ENDPATH**/ ?>