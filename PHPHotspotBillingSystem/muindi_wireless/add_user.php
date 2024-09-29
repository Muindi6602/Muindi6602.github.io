<?php
require 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $phone_number = trim($_POST['phone_number']);
    $phone_type = trim($_POST['phone_type']);
    $mac_address = trim($_POST['mac_address']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, phone_number, phone_type, mac_address, email, password, is_admin) VALUES (?, ?, ?, ?, ?, ?, 0)");
        $stmt->execute([$name, $phone_number, $phone_type, $mac_address, $email, $password]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error adding user: ' . $e->getMessage()]);
    }
}
