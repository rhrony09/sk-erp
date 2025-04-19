<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Service Assignment Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <p>Dear {{ $data->employee->name }},</p>
        <p>I hope this email finds you well. I wanted to inform you that a new service has been added to your
            responsibilities. Please review the details on your dashboard using the link below:</p>
        <a href="{{ route('home') }}" class="btn">Go to Dashboard</a>
        <p>If you have any questions or need further clarification, feel free to reach out.</p>
        <p>Thank you for your continued hard work.</p>
        <p>Best regards,<br>
            {{ config('app.name') }}</p>
    </div>
</body>

</html>
