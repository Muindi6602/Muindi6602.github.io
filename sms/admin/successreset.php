<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Change Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #ffffff;
            width: 400px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .header {
            background-color: #333333;
            color: white;
            text-align: center;
            padding: 20px 0;
        }
        .header img {
            display: block;
            margin: 0 auto 10px auto;
        }
        .content {
            padding: 20px;
            text-align: center;
        }
        .content p {
            font-size: 18px;
            margin: 20px 0;
        }
        .content a {
            display: inline-block;
            background-color: #ff6f00;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }
        .footer {
            background-color: #f5f5f5;
            color: #666666;
            text-align: center;
            padding: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            
            <h1>Password Change Request</h1>
        </div>
        <div class="content">
            <p>You have submitted a password change request!</p>
            <p>Click the reset link sent to your email</p>
            <p>and then</p>
            <a href="login.php">Login</a>
        </div>
        <div class="footer">
            <p>If you are having any issues with your account, please don't hesitate to contact us by via <a href="https://wa.me/254115783375" target="_blank">WhatsApp</a>.</p>
            <p>Thanks!</p>
        </div>
    </div>
</body>
</html>
