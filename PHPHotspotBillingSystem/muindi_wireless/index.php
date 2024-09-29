<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manchester</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .wifi-prompt {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            padding: 20px;
            text-align: center;
            position: relative;
        }

        .wifi-prompt img {
            width: 50px;
            margin-bottom: 20px;
        }

        .wifi-prompt h1 {
            font-size: 18px;
            margin: 0 0 20px;
        }

        .wifi-prompt input[type="password"],
        .wifi-prompt input[type="text"],
        .wifi-prompt input[type="tel"] {
            width: calc(100% - 24px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .wifi-prompt label {
            display: flex;
            align-items: center;
            justify-content: start;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .wifi-prompt label input[type="checkbox"] {
            margin-right: 10px;
        }

        .wifi-prompt button {
            padding: 10px 20px;
            background-color: #007aff;
            border: none;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .wifi-prompt button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        .wifi-prompt button:last-of-type {
            margin-left: 10px;
            background-color: #ccc;
            color: #333;
        }

        .wifi-prompt button:last-of-type:hover {
            background-color: #bbb;
        }

        /* Admin icon in bottom-right corner */
        .admin-icon {
            position: absolute;
            bottom: 10px;
            right: 10px;
            cursor: pointer;
            color: #555;
        }

        .admin-icon i {
            font-size: 24px;
        }

        .admin-icon:hover {
            color: #000;
        }
    </style>
</head>
<body>
    <div class="wifi-prompt">
        <img src="https://img.icons8.com/ios-filled/50/000000/wifi.png" alt="Wi-Fi Icon">
        <h1>The Wi-Fi network "Manchester" requires payment.</h1>
        <form action="process_login.php" method="POST" onsubmit="return validateForm()">
            <input type="tel" name="phone_number" id="phone" placeholder="Phone Number (e.g. 254...)" required pattern="^254[0-9]{9}$">
            <input type="password" name="password" id="password" placeholder="Password" required>
            <label>
                <input type="checkbox" id="show-password"> Show password
            </label>
            <label>
                <input type="checkbox" name="remember" checked> Remember this network
            </label>
            <div>
                <button type="submit">Login</button>
            </div>
            <p>Forgot password? <a href="passwordreset/passwordreset.php">Reset</a></p>
            <p>You don't have an account? <a href="signup.php">Signup</a></p>
        </form>

        <!-- Admin icon in bottom-right corner -->
        <div class="admin-icon" onclick="window.location.href='admin_login.php'">
            <i class="fas fa-user-cog"></i>
        </div>
    </div>

    <script>
        // Show/Hide Password Toggle
        document.getElementById('show-password').addEventListener('change', function () {
            var passwordInput = document.getElementById('password');
            passwordInput.type = this.checked ? 'text' : 'password';
        });

        // Basic form validation
        function validateForm() {
            var phone = document.getElementById('phone').value;
            var password = document.getElementById('password').value;

            if (!/^254[0-9]{9}$/.test(phone)) {
                alert('Please enter a valid phone number (e.g., 2547xxxxxxxx)');
                return false;
            }
            if (password.length < 6) {
                alert('Password must be at least 6 characters long');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
