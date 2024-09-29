<?php
require 'database.php';

if (isset($_POST['message_id']) && isset($_POST['admin_reply'])) {
    $message_id = $_POST['message_id'];
    $admin_reply = $_POST['admin_reply'];

    // Update the message with the admin's reply
    $sql = 'UPDATE messages SET admin_reply = :admin_reply WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':admin_reply' => $admin_reply, ':id' => $message_id]);

    // Redirect back to admin dashboard
    header('Location: admin_dashboard.php');
    exit();
}
?>
