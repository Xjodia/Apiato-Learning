<!DOCTYPE html>
<html lang="en">
<head>
    <title>Product Export Email</title>
</head>
<body>
<p>Dear {{ $emailName }},</p>

<p>Thank you for using our product export service. You can download the exported file using the link below:</p>

<p><a href="{{ $file }}" download="{{ $file }}">Download Exported File</a></p>

<p>Best regards,<br>
    Your Company Name
</p>
</body>
</html>
