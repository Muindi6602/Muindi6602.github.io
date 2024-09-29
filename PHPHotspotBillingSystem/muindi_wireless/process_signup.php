<?php
require 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $phone_number = isset($_POST['phone_number']) ? trim($_POST['phone_number']) : '';
    $phone_type = isset($_POST['phone_type']) ? trim($_POST['phone_type']) : '';
    $mac_address = isset($_POST['mac_address']) ? trim($_POST['mac_address']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit;
    }

    // Check if the email is not empty
    if (empty($email)) {
        echo 'Email is required.';
        exit;
    }

    // Check if email is already registered
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) {
        echo 'Email is already registered.';
        exit;
    }

    // Check if Mac Adress is already registered
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE mac_address = ?");
    $stmt->execute([$mac_address]);
    if ($stmt->fetchColumn() > 0) {
        echo 'Mac Address is already registered.';
        exit;
    }


    // Check if phone number is already registered
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE phone_number = ?");
    $stmt->execute([$phone_number]);
    if ($stmt->fetchColumn() > 0) {
        echo 'Phone number is already registered.';
        exit;
    }


    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute SQL statement
    $stmt = $pdo->prepare("INSERT INTO users (name, phone_number, phone_type, mac_address, email, password) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$name, $phone_number, $phone_type, $mac_address, $email, $hashed_password])) {
        echo "success";
    } else {
        echo "There was an error saving your data.";
    }
}
?>