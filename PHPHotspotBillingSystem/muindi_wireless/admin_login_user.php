<?php
session_start();
require 'database.php'; // Database connection file

// Handle form submission for admin re-authentication
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['admin_login'])) {
    $admin_email = $_POST['admin_email'];
    $admin_password = $_POST['admin_password'];

    // Fetch admin details from the database (plain text password comparison)
    $sql = "SELECT * FROM admins WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$admin_email]);
    $admin = $stmt->fetch();

    // Validate admin credentials (no password hashing)
    if ($admin && $admin_password === $admin['password']) {
        // Re-authentication successful, set session for admin
        $_SESSION['admin_reauthenticated'] = true;
        $_SESSION['admin_id'] = $admin['id'];

        // Now show the phone number input form to log in as a user
        $_SESSION['show_user_login_form'] = true; // Set a session flag
    } else {
        $error = "Invalid admin credentials.";
    }
}

// Handle form submission for user login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login_user'])) {
    if (!isset($_SESSION['admin_reauthenticated']) || $_SESSION['admin_reauthenticated'] !== true) {
        // If admin has not reauthenticated, redirect to login form
        header("Location: admin_login_user.php");
        exit();
    }

    $phone_number = $_POST['phone_number'];

    // Fetch user details from the database
    $sql = "SELECT * FROM users WHERE phone_number = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$phone_number]);
    $user = $stmt->fetch();

    if ($user) {
        // Log in as the user by setting session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_phone'] = $user['phone_number'];
        $_SESSION['logged_in'] = true;

        // Redirect to the user's dashboard
        header("Location: /muindi_wireless/dashboard.php");
        exit();
    } else {
        $error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | User</title>
    <link rel="stylesheet" href="admin_to_user_styles.css"> 

    <style>

.navbar {
            background-color: #2c3e50;
            padding: 10px;
            color: white;
            text-align: center;
            font-size: 20px;
            position: relative;
        }
        .admin-menu {
            position: absolute;
            top: 10px;
            left: 20px;
        }
        .admin-menu .dropdown {
            position: relative;
            display: inline-block;
        }
        .admin-menu .dropdown-content {
            display: none;
            position: absolute;
            background-color: blueviolet;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            min-width: 160px;
            z-index: 1;
            left: 50%;
            transform: translateX(-50%);
        }
        .admin-menu .dropdown-content a {
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            color: black;
        }
        .admin-menu .dropdown-content a:hover {
            background-color: #f1f1f1;
        }
        .admin-menu .dropdown:hover .dropdown-content {
            display: block;
        }

        .logout-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .logout-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (isset($error)) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>

        <!-- Admin Re-Authentication Form -->
        <?php if (!isset($_SESSION['admin_reauthenticated'])) { ?>
            <h2>Spy Login</h2>
            <form method="POST" action="admin_login_user.php" class="form-box">
                <label for="admin_email">Admin Email:</label>
                <input type="email" id="admin_email" placeholder="example@gmail.com" name="admin_email" required>

                <label for="admin_password">Admin Password:</label>
                <input type="password" id="admin_password" name="admin_password" required>

                <button type="submit" name="admin_login">Next</button>
                <br><br>
                <a href="spy_logout.php" class="logout-btn">Cancel</a>

            </form>
        <?php } ?>

        <!-- User Login Form (only displayed if admin is re-authenticated) -->

        <?php if (isset($_SESSION['show_user_login_form']) && $_SESSION['show_user_login_form'] === true) { ?>
            <br><br>


            <div class="navbar">
    <?= htmlspecialchars($admin['name']); ?>, Welcome to the Admin Dashboard
    <div class="admin-menu">
        <div class="dropdown">
            <i class="fas fa-user"></i>
            
        </div>
    </div>
</div>


            <h2>Spy on a user's account</h2><br><br>
            <form method="POST" action="admin_login_user.php" class="form-box">
                <label for="phone_number">Enter User's Phone Number:</label>
                <input type="text" id="phone_number" placeholder="254........." name="phone_number" required>

                <button type="submit" name="login_user">Spy</button>
                <br><br>
                <a href="spy_logout.php" class="logout-btn">Abort</a>
  
            </form>
        <?php } 
        ?>
    </div>
</body>
</html>
