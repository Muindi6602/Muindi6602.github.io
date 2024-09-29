<?php
// Database credentials should ideally be stored in environment variables
$host = getenv('DB_HOST') ?: "localhost";
$user = getenv('DB_USER') ?: "root";
$password = getenv('DB_PASS') ?: "";
$dbname = getenv('DB_NAME') ?: "Manchester";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Ensure UTF-8 encoding for better handling of character sets
    $pdo->exec("SET NAMES 'utf8'");
} catch (PDOException $e) {
    // Optionally log errors instead of displaying them in production
    error_log("Database connection failed: " . $e->getMessage(), 0);
    die("An error occurred while connecting to the database.");
}

?>