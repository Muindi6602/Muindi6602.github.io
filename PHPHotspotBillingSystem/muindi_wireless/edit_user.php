<?php
require 'database.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];

    $stmt = $pdo->prepare("UPDATE users SET name = ?, phone_number = ?, email = ? WHERE id = ?");
    $stmt->execute([$name, $phone_number, $email, $id]);

    header("Location: admin_dashboard.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Edit User</h1>
        <form method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
            <input type="text" name="phone_number" value="<?= htmlspecialchars($user['phone_number']) ?>" required>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            <button type="submit">Save Changes</button>
        </form>
    </div>
</body>
</html>
