<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Change</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: teal;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            box-sizing: border-box;
        }
        .container h2 {
            margin: 0 0 20px 0;
            font-size: 24px;
        }
        .input-group {
            margin-bottom: 15px;
            position: relative;
        }
        .input-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .input-group input[type="password"] {
            padding-right: 40px;
        }
        .input-group .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
        .password-strength {
            font-size: 12px;
            color: #777;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #4285f4;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            text-align: center;
        }
        .btn:hover {
            background-color: #357ae8;
        }
        .container a {
            color: #4285f4;
            text-decoration: none;
        }
        .container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>Password</h3>
        <p>Choose a strong password and don't reuse it for other accounts. <a href="#">Learn more</a></p>
        <p>Changing your password will sign you out of all your devices. You will need to enter your new password on all your devices.</p>
        
        <div class="input-group">
            <label for="new-password">New password</label>
            <input type="password" id="new-password" name="new-password">
            <span class="toggle-password" onclick="togglePasswordVisibility('new-password')">👁️</span>
        </div>
        
        <div class="input-group">
            <label for="confirm-password">Confirm new password</label>
            <input type="password" id="confirm-password" name="confirm-password">
            <span class="toggle-password" onclick="togglePasswordVisibility('confirm-password')">👁️</span>
        </div>
        
        <p class="password-strength">
            Password strength:<br>
            Use at least 4 characters. Don’t use a password from another site. <br><br>Remember Password? <a href="/sms/admin/login.php">Login</a>
        </p>
        
        <button class="btn" onclick="changePassword()">CHANGE PASSWORD</button>
    </div>

    <script>
        function togglePasswordVisibility(fieldId) {
            var field = document.getElementById(fieldId);
            if (field.type === "password") {
                field.type = "text";
            } else {
                field.type = "password";
            }
        }

        function changePassword() {
            var newPassword = document.getElementById('new-password').value;
            var confirmPassword = document.getElementById('confirm-password').value;
            
            if (newPassword === confirmPassword) {
                alert('Password changed successfully!');
                // Here you would typically send the new password to the server
            } else {
                alert('Passwords do not match. Please try again.');
            }
        }
    </script>
</body>
</html>
