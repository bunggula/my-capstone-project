<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Account</title>
</head>
<body>
    <h2>Hello {{ $user->first_name }},</h2>

    <p>Your account has been created. Here are your login details:</p>

    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Temporary Password:</strong> {{ $tempPassword }}</p>

    <p>Please login and change your password immediately.</p>

    <br>
    <p>Regards,<br>Laovista Team</p>
</body>
</html>
