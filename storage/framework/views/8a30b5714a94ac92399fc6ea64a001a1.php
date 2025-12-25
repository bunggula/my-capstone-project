<!DOCTYPE html>
<html>
<head>
    <title>Registration Approved</title>
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
            background-color: #1d4ed8;
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

        <p>We are pleased to inform you that your registration as a resident in our system has been <strong>approved</strong>.</p>

        <p>You may now log in to your account</a> to access the services available.</p>

        <p>If you have any questions or require assistance, please do not hesitate to contact our office.</p>

        <p>Thank you for your cooperation and welcome to the community!</p>

        <p>Sincerely,<br>
Barangay Secretary's Office</p>

<?php if(isset($approver)): ?>
<p><em>Approved by: <?php echo e($approver->full_name); ?></em></p>
<?php endif; ?>


        <div class="footer">
            <p>This is an automated message. Please do not reply directly to this email.</p>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/emails/resident-approved.blade.php ENDPATH**/ ?>