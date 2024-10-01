<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #2c3e50;
            font-family: Arial, sans-serif;
        }

        .container {
            text-align: center;
        }

        .message-box {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 300px;
            margin: auto;
        }

        .checkmark {
            font-size: 48px;
            color: #2ecc71;
        }

        h1 {
            margin: 20px 0 10px;
            font-size: 24px;
            color: #333333;
        }

        p {
            color: #666666;
            margin-bottom: 20px;
        }

        button {
            background-color: #2ecc71;
            border: none;
            color: #ffffff;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background-color: #27ae60;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="message-box">
            <div class="checkmark">&#10004;</div>
            <h1>Almost there</h1><br>
            <p>A SimToolkit Push has been sent to customers phone.<br><br> Enter Mpesa Pin to complete the transaction.</p>
            <button id="okButton">EXIT</button>
        </div>
    </div>
    <script>
        document.getElementById('okButton').addEventListener('click', function() {
            window.history.go(-2);
        });
    </script>
</body>
</html>
