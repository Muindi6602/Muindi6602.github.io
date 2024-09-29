<?php
require 'database.php'; // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone_number = $_POST['phone_number'];
    $voucher_price = $_POST['voucher_price'];

    // Add code here to process the voucher purchase
    // Example: Insert record into a `vouchers` table or send an SMS confirmation

    echo "<h1>Thank you for purchasing a voucher worth Ksh $voucher_price!</h1>";
    echo "<a href='dashboard.php'>Go back to Dashboard</a>";
}
?>
