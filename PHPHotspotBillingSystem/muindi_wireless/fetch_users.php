<?php
require 'database.php';

$stmt = $pdo->prepare("SELECT id, name, email, phone_type, mac_address, phone_number, password FROM users WHERE is_admin = 0");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($users);
