<?php
session_start();
require 'database.php';

if (isset($_SESSION['user_id'])) {
    // Update the `is_logged_in` status to 0 (logged out)
    $stmt = $pdo->prepare("UPDATE users SET is_logged_in = 0 WHERE id = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
}

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Ensure that the session cookie is deleted
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redirect to the homepage
header("Location: index.php");
exit;
?>
