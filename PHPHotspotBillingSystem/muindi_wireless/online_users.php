<?php
require 'database.php';

// Fetch online users
$stmt = $pdo->prepare("SELECT name AS username, last_activity FROM users WHERE is_logged_in = 1");
$stmt->execute();
$online_users = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($online_users);
?>
