<!DOCTYPE html>
<html>
<head>
    <title>OTP Verification</title>
</head>
<body>
    <h1>Hello, {{ $user->name }}</h1>
    <p>Your OTP for verification is: <strong>{{ $otp }}</strong></p>
    <p>Thank you for registering with us!</p>
</body>
</html>