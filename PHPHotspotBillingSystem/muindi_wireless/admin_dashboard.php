<?php
session_start();
require 'database.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    // Redirect to login page if not logged in
    header("Location: admin_login.php");
    exit;
}

// Require the necessary PHPMailer files
require 'database.php'; // Ensure you include your database connection
require 'passwordreset/PHPMailer/src/Exception.php';
require 'passwordreset/PHPMailer/src/PHPMailer.php';
require 'passwordreset/PHPMailer/src/SMTP.php';

// Initialize the $allSent variable
$allSent = true; // Set it to true at the beginning

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if this is a bulk email action
    if (isset($_POST['action']) && $_POST['action'] === 'send_bulk_email') {
        $subject = $_POST['subject'];
        $message = $_POST['message'];

        try {
            // Fetch all users' emails and names from the database
            $stmt = $pdo->prepare("SELECT name, email FROM users");
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($users) {
                // Loop through users and send the email
                foreach ($users as $user) {
                    $userName = $user['name'];
                    $userEmail = $user['email'];

                    // Initialize PHPMailer here for each user
                    $mail = new PHPMailer\PHPMailer\PHPMailer();
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = '****************'; // Your email address
                    $mail->Password = '****************'; // Your app password
                    $mail->SMTPSecure = 'tls'; // TLS encryption
                    $mail->Port = 587;

                    // Email content
                    $mail->setFrom('****************', 'Manchester'); // replace with your email address
                    $mail->addAddress($userEmail, $userName); // Recipient
                    $mail->isHTML(true);
                    $mail->Subject = $subject;
                    $mail->Body = "Hello " . htmlspecialchars($userName) . ",<br><br>" . nl2br(htmlspecialchars($message));

                    // Send email
                    if (!$mail->send()) {
                        echo "<script>Swal.fire('Error!', 'Message could not be sent to {$userEmail}. Mailer Error: " . $mail->ErrorInfo . "', 'error');</script>";
                        $allSent = false; // Set flag to false if any email fails
                        break; // Exit the loop if an error occurs
                    }
                }

                // After sending emails
                if ($allSent) {
                    echo "<script>Swal.fire('Sent!', 'Emails have been sent successfully.', 'success');</script>";
                }
            } else {
                echo "<script>Swal.fire('Info', 'No users found to send emails to.', 'info');</script>";
            }
        } catch (PDOException $e) {
            echo "<script>Swal.fire('Error!', 'Database error: " . $e->getMessage() . "', 'error');</script>";
        }
    }
}


// Fetch admin details
$stmt = $pdo->prepare("SELECT name FROM admins WHERE id = ?");
$stmt->execute([$_SESSION['admin_id']]);
$admin = $stmt->fetch();


// Delete user functionality
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    
    // Prepare and execute the deletion statement
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    
    // Redirect to the admin dashboard after deletion
    header("Location: admin_dashboard.php"); 
    exit;
}


// Database connection
$host = 'localhost'; // Your host
$dbname = 'Manchester'; // Your database
$user = 'root'; // Your database user
$pass = ''; // Your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle package add, edit, delete
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'add') {
            $code = $_POST['code'];
            $price = $_POST['price'];

            // Insert the new voucher
            $stmt = $pdo->prepare("INSERT INTO vouchers (code, price) VALUES (:code, :price)");
            $stmt->execute(['code' => $code, 'price' => $price]);
        } elseif ($_POST['action'] == 'edit') {
            $id = $_POST['id'];
            $code = $_POST['code'];
            $price = $_POST['price'];

            // Update the voucher
            $stmt = $pdo->prepare("UPDATE vouchers SET code = :code, price = :price WHERE id = :id");
            $stmt->execute(['code' => $code, 'price' => $price, 'id' => $id]);
        } elseif ($_POST['action'] == 'delete') {
            $id = $_POST['id'];

            // Delete the voucher
            $stmt = $pdo->prepare("DELETE FROM vouchers WHERE id = :id");
            $stmt->execute(['id' => $id]);
        }

        // Redirect after processing the form submission
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Fetch vouchers after redirection
$vouchers = [];
$stmt = $pdo->query("SELECT * FROM vouchers");
$vouchers = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Delete message from database
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = 'DELETE FROM messages WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $delete_id]);
    header("Location: admin_dashboard.php"); // Redirect after deletion
    exit;
}

// Fetch all messages
$sql = 'SELECT * FROM messages ORDER BY Time DESC';
$stmt = $pdo->query($sql);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }
        .container {
            width: 90%;
            margin: 0 auto;
            padding: 20px;
        }
        .navbar {
            background-color: #2c3e50;
            padding: 10px;
            color: white;
            text-align: center;
            font-size: 20px;
            position: relative;
        }
        .admin-menu {
            position: absolute;
            top: 10px;
            left: 20px;
        }
        .admin-menu .dropdown {
            position: relative;
            display: inline-block;
        }
        .admin-menu .dropdown-content {
            display: none;
            position: absolute;
            background-color: blueviolet;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            min-width: 160px;
            z-index: 1;
            left: 50%;
            transform: translateX(-50%);
        }
        .admin-menu .dropdown-content a {
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            color: black;
        }
        .admin-menu .dropdown-content a:hover {
            background-color: #f1f1f1;
        }
        .admin-menu .dropdown:hover .dropdown-content {
            display: block;
        }
        .dashboard {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        .dashboard-card {
            background-color: #3498db;
            color: white;
            border-radius: 8px;
            padding: 20px;
            width: 30%;
            text-align: center;
            transition: all 0.3s ease;
        }
        .dashboard-card:hover {
            transform: scale(1.05);
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
        }
        .dashboard-card i {
            font-size: 50px;
            margin-bottom: 10px;
        }
        .active-users {
            margin-top: 30px;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #3498db;
            color: white;
        }


 /* Modal styles */
 .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            align-items: center;
            justify-content: center;
            animation: fadeIn 1s ease;
        }
        .modal-content {
            background-color: white;
            border-radius: 15px;
            padding: 30px;
            width: 50%;
            max-width: 500px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            transform: scale(0);
            animation: zoomIn 0.5s ease forwards;
        }
        .close {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 28px;
            cursor: pointer;
        }
        .close:hover {
            color: #3498db;
        }
        .modal-content input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 2px solid #3498db;
            border-radius: 8px;
        }
        .submit-button {
            width: 100%;
            padding: 10px;
            background-color: #3498db;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        .submit-button:hover {
            background-color: #2980b9;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes zoomIn {
            from { transform: scale(0); }
            to { transform: scale(1); }
        }


         /* Packages styles */

        /* Dashboard card styling with unique class and ID */
        .dashboard-card-livaron {
            background-color: #3498db;
            color: white;
            border-radius: 8px;
            padding: 20px;
            width: 30%;
            text-align: center;
            transition: all 0.3s ease;
        }

        .dashboard-card-livaron:hover {
            transform: scale(1.05);
        }

        .dashboard-card-livaron i {
            font-size: 40px;
            color: white;
            margin-bottom: 10px;
        }

        .dashboard-card-livaron h2 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .dashboard-card-livaron p {
            color: white;
        }

        /* Hide the package section initially */
        .package-section {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #ffffff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }
        }


        .close-btn {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .close-btn:hover {
            background-color: red;
        }

        .wrapper-zanuxi {
            background: linear-gradient(145deg, #e3e3e3, #ffffff);
            box-shadow: 10px 10px 30px #c8c8c8, -10px -10px 30px #ffffff;
            border-radius: 20px;
            padding: 30px;
            width: 90%;
            max-width: 800px;
            transform: rotateY(30deg);
            transition: transform 0.5s ease;
        }

        .wrapper-zanuxi:hover {
            transform: rotateY(0deg);
        }

        .title-gavlizo {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 20px;
            color: #333;
        }

        .form-bovelane {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form-bovelane input, .form-bovelane button {
            padding: 10px;
            font-size: 1rem;
            border: none;
            border-radius: 10px;
            box-shadow: 5px 5px 15px #c8c8c8, -5px -5px 15px #ffffff;
        }

        .btn-florisano {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-florisano:hover {
            background-color: #45a049;
        }

        .table-veflon {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table-veflon th, .table-veflon td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        .table-veflon th {
            background-color: #f2f2f2;
        }

        .btn-edit-zivano, .btn-delete-orleone {
            background-color: #ffcc00;
            color: black;
            padding: 5px 10px;
            cursor: pointer;
            margin: 5px;
        }

        .btn-delete-orleone {
            background-color: #f44336;
            color: white;
        }

                /* ID Column */
        .table-veflon td:nth-child(1), 
        .table-veflon th:nth-child(1) {
            color: blue; 
        }

        /* Code Column */
        .table-veflon td:nth-child(2), 
        .table-veflon th:nth-child(2) {
            color: blue; 
        }

        /* Price Column */
        .table-veflon td:nth-child(3), 
        .table-veflon th:nth-child(3) {
            color: blue; 
        }

         /* Time Column */
         .table-veflon td:nth-child(4), 
        .table-veflon th:nth-child(4) {
            color: blue; 
        }

         /* Action Column*/
         .table-veflon td:nth-child(5), 
        .table-veflon th:nth-child(5) {
            color: red; 
        }


      /* messages from users */
      .fancy_table_container {
            width: 80%;
            margin: 50px auto;
            border-collapse: collapse;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }

        .fancy_table {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            border-radius: 10px;
            overflow: hidden;
        }

        .fancy_table th, .fancy_table td {
            padding: 15px;
            text-align: left;
        }

        .fancy_table thead {
            background-color: #2c3e50;
            color: white;
        }

        .fancy_table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .action_button_delete {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        .action_button_reply {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        .action_button_delete:hover {
            background-color: #c0392b;
        }

        .action_button_reply:hover {
            background-color: #2980b9;
        }

        .table_header_centered {
            text-align: center;
        }


        /* Send message to users div */
        .msg_container-fsd {
            max-width: 500px;
            margin: 50px auto;
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .msg_title-ffs {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .msg_form-ffs {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .msg_input-ffs {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .msg_textarea-ffs {
            height: 50px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            resize: none;
        }

        .msg_button-ffs {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .msg_button-ffs:hover {
            background-color: #45a049;
        }

        .msg_button-ffs:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

  /* online users */
  .centered-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh; /* Full viewport height */
            background-color: #f9f9f9;
        }

        .check-button {
            padding: 10px 20px;
            background-color: #4caf50; /* Green background */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .check-button:hover {
            background-color: #45a049; /* Darker green on hover */
        }

        .online-list {
            display: none;
            margin-top: 20px;
            list-style-type: none;
            padding: 0;
            max-width: 300px; /* Limit width for better presentation */
            background-color: #fff; /* White background */
            border: 1px solid #ddd; /* Border for the list */
            border-radius: 5px; /* Rounded corners */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        .online-user {
            padding: 10px;
            border-bottom: 1px solid #ddd; /* Separator line */
        }

        .online-user:last-child {
            border-bottom: none; /* Remove last separator */
        }

        .online-user-number {
            font-weight: bold; /* Bold for user number */
            color: #4caf50; /* Green color for numbers */
        }

        /* The Modal (background) */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4); /* Black with opacity */
}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 300px;
    height: 400px;
    border-radius: 10px;
    display: flex;
    flex-direction: column;
}

/* Chat Window */
.chat-window {
    display: flex;
    flex-direction: column;
    flex-grow: 1;
    margin-top: 10px;
}

/* Messages Container */
.messages {
    flex-grow: 1;
    background-color: #f1f1f1;
    padding: 10px;
    border-radius: 5px;
    overflow-y: scroll;
}

/* Chat Input */
.chat-input {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

/* Close Button */
.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover, .close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}
    </style>




</head>
<body>

<div class="navbar">
    <?= htmlspecialchars($admin['name']); ?>, Welcome to the Admin Dashboard
    <div class="admin-menu">
        <div class="dropdown">
            <i class="fas fa-user"></i>
            <div class="dropdown-content">
                <a href="admin_login_user.php">Spy</a>
                <a href="admin_logout.php">Logout</a>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="dashboard">
        <div class="dashboard-card">
            <i class="fas fa-users"></i>
            <h2>Registered Users</h2>
            <p id="userCount">Loading...</p>
        </div>

        <div class="dashboard-card" id="addUserButton">
            <i class="fas fa-user-plus"></i>
            <h2>Add Users</h2>
            <p>Add new users to the portal</p>
        </div>

          <!-- Dashboard Card with unique ID and class -->
    <div class="dashboard-card-livaron" id="manageVouchersBtnUnique">
        <i class="fas fa-cog"></i>
        <h2>Manage Packages</h2>
        <p>Control the packages</p>
    </div>


<!-- Live Chat Dashboard Card -->
<div class="dashboard-card-livaron" id="liveChat" onclick="openChatModal()">
    <i class="fas fa-comments"></i>
    <h2>Live Chat</h2>
    <p>0 Chat</p>
</div>


    </div>

    <!-- Registered Users List -->
    <div class="active-users fade-in">
        <h3>Registered Users</h3>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Phone Type</th>
                    <th>Mac Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                <!-- User data will be populated here dynamically -->
            </tbody>
        </table>
    </div>

    <!-- Modal for adding users -->
    <div id="addUserModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Add a New User</h3>
            <form id="addUserForm">
                <input type="text" name="name" placeholder="Name" required>
                <input type="text" name="phone_number" placeholder="Phone Number" required>
                <input type="text" name="phone_type" placeholder="Phone Type" required>
                <input type="text" name="mac_address" placeholder="Mac Address" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" class="submit-button">Add User</button>
            </form>
        </div>
    </div>

</div>

<script>
    var modal = document.getElementById("addUserModal");
    var btn = document.getElementById("addUserButton");
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal 
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // Close the modal if the user clicks outside of it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Fetch the users initially
    function fetchUsers() {
        fetch('fetch_users.php')
            .then(response => response.json())
            .then(data => {
                const userTableBody = document.getElementById('userTableBody');
                const userCount = document.getElementById('userCount');
                userTableBody.innerHTML = '';
                userCount.textContent = data.length + ' Users';

                data.forEach(user => {
                    const row = `<tr>
                        <td>${user.name}</td>
                        <td>${user.email}</td>
                        <td>${user.phone_number}</td>
                        <td>${user.phone_type}</td>
                        <td>${user.mac_address}</td>
                        <td>
                            <form id="deleteForm-${user.id}" method="POST" action="admin_dashboard.php" style="display: inline;">
                            <input type="hidden" name="user_id" value="${user.id}">
                            <input type="hidden" name="delete_user" value="1">
                            <button type="button" onclick="confirmDeletion(${user.id})" style="background: none; border: none; color: red; cursor: pointer;">
                                <i class="fas fa-trash"></i>
                            </button>
                            </form>
                        </td>
</tr>`;
                    userTableBody.innerHTML += row;
                });
            });
    }

    // Submit the Add User form via AJAX
    document.getElementById('addUserForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        fetch('add_user.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Success!', 'User added successfully', 'success');
                fetchUsers();  // Refresh the users list
                modal.style.display = "none";  // Close modal
            } else {
                Swal.fire('Error!', data.message, 'error');
            }
        });
    });

    // Initial fetch of users
    fetchUsers();
</script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDeletion(userId) {
    Swal.fire({
        title: 'Delete the user?',
        text: "This will delete the user!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteForm-' + userId).submit();
        }
    });
}
</script>


                    

<!-- SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<br>
<br>
<br>
<br>

    <!-- Package Section (initially hidden) -->

     <div class="package-section" id="packageSection">
        <div class="wrapper-zanuxi">
        <button class="close-btn" id="closeBtn">Close</button>
            <h2 class="title-gavlizo">Manchester Packages</h2>
            <form class="form-bovelane" method="POST">
                <input type="text" name="code" placeholder="Package duration(e.g. 1hr)" required>
                <input type="number" step="0.01" name="price" placeholder="Price (KES)" required>
                <input type="hidden" name="action" value="add">
                <button type="submit" class="btn-florisano">Add Package</button>
            </form>

            <table class="table-veflon">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Code</th>
                        <th>Price</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vouchers as $voucher): ?>
                        <tr>
                            <td><?= $voucher['id'] ?></td>
                            <td><?= $voucher['code'] ?></td>
                            <td><?= $voucher['price'] ?></td>
                            <td><?= $voucher['created_at'] ?></td>
                            <td>
                                <!-- Edit form -->
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $voucher['id'] ?>">
                                    <input type="hidden" name="code" value="<?= $voucher['code'] ?>">
                                    <input type="hidden" name="price" value="<?= $voucher['price'] ?>">
                                    <input type="hidden" name="action" value="edit">
                                    <button type="submit" class="btn-edit-zivano">Edit</button>
                                </form>
                                <!-- Delete form -->
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $voucher['id'] ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button type="submit" class="btn-delete-orleone">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Get the elements
        const manageVouchersBtn = document.getElementById('manageVouchersBtnUnique');
        const packageSection = document.getElementById('packageSection');
        const closeBtn = document.getElementById('closeBtn');

        // Show package section on button click
        manageVouchersBtn.addEventListener('click', function() {
            packageSection.style.display = 'block';
        });

        // Close button functionality
        closeBtn.addEventListener('click', function() {
            packageSection.style.display = 'none';
        });

      

    </script>



<div class="fancy_table_container">
        <h3>Messages</h3>

        <table class="fancy_table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Message</th>
                    <th>Time</th>
                    <th class="table_header_centered">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $message): ?>
                    <tr>
                        <td><?= htmlspecialchars($message['name']) ?></td>
                        <td><?= htmlspecialchars($message['user_message']) ?></td>
                        <td><?= $message['Time'] ?></td>
                        <td class="table_header_centered">
                            <!-- Delete Option -->
                            <a href="admin_dashboard.php?delete_id=<?= $message['id'] ?>" class="action_button_delete" onclick="return confirm('Are you sure you want to delete this message?')">Delete</a>

                            <!-- Reply Form -->
                            <form method="POST" action="admin_reply.php" style="display:inline;">
                                <input type="hidden" name="message_id" value="<?= $message['id'] ?>">
                                <textarea name="admin_reply" placeholder="Reply..." rows="1" style="width: 150px;"></textarea>
                                <button type="submit" class="action_button_reply">Reply</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<br><br>


<!-- send messages to users once -->

<div class="msg_container-fsd">
    <div class="msg_title-ffs">Send Bulk Mails to Registered Users</div>
    <form class="msg_form-ffs" id="bulkMessageForm" method="POST">
        <input type="hidden" name="action" value="send_bulk_email"> <!-- Add this line -->
        <input type="text" id="msgSubject" name="subject" class="msg_input-ffs" placeholder="Subject" required>
        <textarea id="msgContent" name="message" class="msg_textarea-ffs" placeholder="Enter your message here..." required></textarea>
        <button type="submit" class="msg_button-ffs" id="sendMessageButton">Send</button>
    </form>
</div>


    <script>
        // Handle form submission
        document.getElementById('bulkMessageForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const subject = document.getElementById('msgSubject').value;
            const content = document.getElementById('msgContent').value;

            if (!subject || !content) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please fill out both the subject and message fields!',
                });
                return;
            }

            Swal.fire({
                title: 'Email Bulk Send',
                text: "Are you sure you want to send this email to all registered users?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, send it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form to the same page
                    this.submit();
                }
            });
        });
    </script>

<!-- Online users icon -->
<div class="centered-container">
    <h2>Online users</h2>

    <!-- Check button -->
    <button class="check-button" onclick="showOnlineUsers()">Check Online Users</button>

    <!-- List of online users -->
    <div class="online-list" id="onlineUsers"></div>
</div>


<script>
        function showOnlineUsers() {
            const onlineUsersDiv = document.getElementById('onlineUsers');
            onlineUsersDiv.innerHTML = '';

            // Fetch the online users via AJAX
            fetch('online_users.php')
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        data.forEach((user, index) => {
                            let userDiv = document.createElement('div');
                            userDiv.className = 'online-user';
                            userDiv.textContent = `${index + 1}. ${user.username} (Last Activity: ${user.last_activity})`;
                            onlineUsersDiv.appendChild(userDiv);
                        });
                    } else {
                        onlineUsersDiv.innerHTML = '<div class="online-user">No users online.</div>';
                    }

                    // Show the users
                    document.querySelector('.online-list').style.display = 'block';
                });
        }
    </script>



<!-- Chat Modal -->
<div id="chatModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeChatModal()">&times;</span>
        <h2>Live Chat</h2>
        <div class="chat-window">
            <div class="messages">
                <!-- Messages will go here -->
            </div>
            <input type="text" class="chat-input" placeholder="Type a message...">
        </div>
    </div>
</div>

<script>
    // Function to open the chat modal
function openChatModal() {
    document.getElementById('chatModal').style.display = 'block';
}

// Function to close the chat modal
function closeChatModal() {
    document.getElementById('chatModal').style.display = 'none';
}

</script>
</body>
</html>