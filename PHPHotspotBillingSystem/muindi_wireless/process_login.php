<?php
require 'database.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone_number = $_POST['phone_number'];
    $password = $_POST['password'];

    try {
        // Prepare the query to fetch the user based on phone number
        $stmt = $pdo->prepare("SELECT * FROM users WHERE phone_number = ?");
        $stmt->execute([$phone_number]);
        $user = $stmt->fetch();

        // Check if user exists and verify the password
        if ($user && password_verify($password, $user['password'])) {
            // Store user ID in session
            $_SESSION['user_id'] = $user['id'];

            // Update login status and last activity
            $updateStmt = $pdo->prepare("UPDATE users SET is_logged_in = 1, last_activity = NOW() WHERE id = :id");
            $updateStmt->execute(['id' => $user['id']]);

            // Redirect to the dashboard
            header("Location: dashboard.php");
            exit;
        } else {
            // Invalid login credentials
            echo "Invalid login credentials.";
        }
    } catch (PDOException $e) {
        // Handle database query errors
        die("Database error: " . $e->getMessage());
    }
}
?>
