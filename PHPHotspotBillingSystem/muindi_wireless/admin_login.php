<?php
require 'database.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check if admin exists
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ? AND password = ?");
    $stmt->execute([$email, $password]);
    $admin = $stmt->fetch();

    if ($admin) {
        // Store admin session
        $_SESSION['admin_id'] = $admin['id'];
        
        // Redirect to the admin dashboard
        header("Location: admin_dashboard.php");
        exit;
    } else {
        echo "<script>alert('Invalid login credentials.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button[type="submit"] {
    background-color: #4CAF50;
    color: white;
    padding: 15px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    width: 15%;
}

button[type="submit"]:hover {
    background-color: #45a049;
}

        button:hover {
            background-color: #005bb5;
        }

        .container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Login</h1>
        <form method="POST" action="admin_login.php">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
<br>
        <p>Back to <a href="index.php">Users</a></p>

    </div>
</body>
</html>
