<?php
session_start(); // Start the session

// Include the database connection file
include('../database.php'); // Adjust the path to your database.php file

// Require the necessary PHPMailer files
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Initialize a variable for the message
$message = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];

    // Prepare and execute the SELECT statement using PDO
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND phone_number = ?");
    $stmt->execute([$email, $phone_number]);

    // Fetch the result
    if ($stmt->rowCount() > 0) {
        // User found, reset the password
        $newPassword = generatePassword(); // Generate a random 7-character password
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT); // Hash the new password

        // Update the password in the database
        $updateStmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ? AND phone_number = ?");
        if ($updateStmt->execute([$hashedPassword, $email, $phone_number])) {
            // Send email with the new password
            if (sendResetEmail($email, $newPassword)) {
                $_SESSION['alert'] = [
                    'type' => 'success',
                    'message' => 'Password changed successfully. Check your email for the new password.'
                ];
            } else {
                $_SESSION['alert'] = [
                    'type' => 'error',
                    'message' => 'Failed to send email. Please try again.'
                ];
            }
        } else {
            $_SESSION['alert'] = [
                'type' => 'error',
                'message' => 'Failed to update password. Please try again.'
            ];
        }
    } else {
        $_SESSION['alert'] = [
            'type' => 'error',
            'message' => 'No user found with the provided email and phone number.'
        ];
    }

    // Redirect to the same page to show the alert
    header('Location: passwordreset.php');
    exit();
}

// Function to generate a random 7-character password
function generatePassword() {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    return substr(str_shuffle($characters), 0, 7);
}

// Function to send reset email using PHPMailer
function sendResetEmail($email, $newPassword) {
    $mail = new PHPMailer\PHPMailer\PHPMailer(); // Use the namespace
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = '****************'; // Your Gmail address
        $mail->Password = '****************'; // Your new App Password
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->SMTPDebug = 0; // Enable verbose debug output
        $mail->Debugoutput = 'html'; // Set the debug output format

        // Recipients
        $mail->setFrom('****************', 'Manchester');  // replace with your gamil address
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset';
        $mail->Body = "Your new password for Manchester is: <strong>$newPassword</strong>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Handle alert display
$alert = isset($_SESSION['alert']) ? $_SESSION['alert'] : null;
unset($_SESSION['alert']); // Clear the alert after using it
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 CDN -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .reset-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group input:focus {
            border-color: #007bff;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            margin-top: 20px;
            text-align: center;
            color: red;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <h2>Reset Your Password</h2>
        <span style="color: red; font-style: italic;">
    (The email and phone number should match the ones entered during registration to facilitate changes)
</span>

<br><br>
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" placeholder="example@gmail.com" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" placeholder="254..........." id="phone_number" name="phone_number" required>
            </div>
            <br>
            <div class="g-recaptcha" data-sitekey="6LdjjpQpAAAAAA1UOTIhPXwvALuUkO9DwywxOoh3"></div>
            <br>
            <button type="submit">Reset Password</button>
            <br><br>
            <p>Remember password? <a href="../index.php">Login</a></p>

        </form>
        <?php if ($alert): ?>
            <script>
                Swal.fire({
                    title: "<?php echo htmlspecialchars($alert['type'] === 'success' ? 'Success!' : 'Error!'); ?>",
                    text: "<?php echo htmlspecialchars($alert['message']); ?>",
                    icon: "<?php echo htmlspecialchars($alert['type']); ?>",
                    confirmButtonText: "<?php echo $alert['type'] === 'success' ? 'Login' : 'Retry'; ?>",
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        if ("<?php echo $alert['type']; ?>" === 'success') {
                            window.location.href = "../index.php"; // Redirect to login page
                        } else {
                            window.location.href = "passwordreset.php"; // Redirect back to password reset page
                        }
                    }
                });
            </script>
        <?php endif; ?>
    </div>
</body>
</html>
