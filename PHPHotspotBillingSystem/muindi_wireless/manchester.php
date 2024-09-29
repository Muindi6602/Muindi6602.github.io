<?php
session_start(); // Ensure session is started

// Function to check if the user is logged in
function checkIfLoggedIn() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit();
    }
}

// Check if the user is logged in
checkIfLoggedIn();

// Include the database connection
include 'database.php';

// Check for PDO connection
if (!isset($pdo) || !$pdo) {
    die('Database connection failed');
}

$user_id = $_SESSION['user_id'];

try {
    // Prepare and execute the SQL query to get user details
    $stmt = $pdo->prepare("SELECT name, phone_type FROM users WHERE id = :id");
    $stmt->execute([':id' => $user_id]);
    
    // Fetch the results
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Ensure the result contains data
    if ($result) {
        $name = $result['name'];
        $phone_type = $result['phone_type'];
    } else {
        die("User not found.");
    }

    // Fetch user messages
    $user_name = $name; // Use the name fetched from the database
    $query = $pdo->prepare("SELECT * FROM messages WHERE name = :name ORDER BY Time DESC");
    $query->execute([':name' => $user_name]);
    $messages = $query->fetchAll(PDO::FETCH_ASSOC);

    // Initialize unreadMessages count for admin replies
    $unreadMessages = 0;
    foreach ($messages as $message) {
        if (!empty($message['admin_reply']) && (empty($message['admin_reply_read_status']) || $message['admin_reply_read_status'] == 0)) {
            $unreadMessages++;
        }
    }

    // Mark message as read
    if (isset($_GET['mark_read_id'])) {
        $mark_read_id = $_GET['mark_read_id'];
        $sql = 'UPDATE messages SET admin_reply_read_status = 1 WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $mark_read_id]);
        header("Location: Manchester.php"); // Redirect after marking as read
        exit;
    }

    // Delete admin reply from database
    if (isset($_GET['delete_id'])) {
        $delete_id = $_GET['delete_id'];
        $sql = 'UPDATE messages SET admin_reply = NULL, admin_reply_read_status = NULL WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $delete_id]);
        header("Location: Manchester.php"); // Redirect after deletion
        exit;
    }

} catch (PDOException $e) {
    die('Query failed: ' . htmlspecialchars($e->getMessage()));
}

// Close the PDO connection
$pdo = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manchester Portal</title>
    <link rel="stylesheet" href="manstyles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>





<style>

/* Styles for the dashboard card */
.dashboard-card-livaron {
    position: fixed;  /* Fixed positioning to stay at the top right even when scrolling */
    top: 20px;        /* Distance from the top of the screen */
    right: 180px;      /* Distance from the right edge of the screen */
    width: 80px;     /* Adjust width as needed */
    height: 60px;    /* Adjust height as needed */
    background-color: pink;
    border-radius: 10px; /* Rounded corners */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Shadow for depth */
    padding: 15px;
    text-align: center; /* Center text and elements */
    cursor: pointer; /* Pointer cursor on hover */
    transition: transform 0.2s ease-in-out; /* Smooth hover effect */
    z-index: 1000; /* Ensures it's above other content */
}

/* Hover effect */
.dashboard-card-livaron:hover {
    transform: scale(1.05); /* Slight scaling on hover */
}

/* Styles for the icon */
.dashboard-card-livaron i {
    position: absolute;
    top: 10px;        /* Positioned near the top */
    right: 10px;      /* Positioned near the right */
    font-size: 16px;  /* Smaller icon size */
    color: #6c757d;   /* Grey color */
}

/* Styles for the header (H2) */
.dashboard-card-livaron h2 {
    margin-top: 40px; /* Space below the icon */
    font-size: 18px;
    color: #343a40;   /* Darker text color */
}

/* Styles for the paragraph */
.dashboard-card-livaron p {
    font-size: 14px;
    color: #6c757d;   /* Grey text color */
    margin-top: 10px; /* Space below the heading */
}

/* Message styles */
.modal-window-kofira {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content-ugikalo {
    background-color: #fff;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 400px;
    border-radius: 8px;
}

.close-button-helago {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close-button-helago:hover,
.close-button-helago:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

.tagged-message-vireba {
    font-size: 16px;
    margin-bottom: 15px;
}

.delete-button-zalu {
    background-color: #ff4c4c;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.delete-button-zalu:hover {
    background-color: #ff1f1f;
}

.mark-read-button-neso {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-left: 10px;
}

.mark-read-button-neso:hover {
    background-color: #45a049;
}


</style>









<body>
    <div class="container">
        <header>
            <div class="navbar">
                <a href="logout.php" class="sign-out">Sign out</a>
            </div>
        </header>

        <!-- Notification Dashboard Card -->
        <div class="dashboard-card-livaron" id="liveChat" onclick="openChatModal()">
            <i class="fas fa-comments"></i>
            <p id="messageCount"><?= $unreadMessages ?> Message<?= $unreadMessages > 1 ? 's' : '' ?></p>
        </div>

        <!-- Chat Modal -->
        <div id="chatModal" class="modal-window-kofira" style="display:none;">
            <div class="modal-content-ugikalo">
                <span class="close-button-helago" onclick="closeChatModal()">&times;</span>
                <?php if (!empty($messages)): ?>
                    <?php foreach ($messages as $message): ?>
                        <p class="tagged-message-vireba">Message: "<?= htmlspecialchars($message['user_message']) ?>"</p>
                        <?php if (!empty($message['admin_reply'])): ?>
                            <p class="tagged-message-vireba">Admin Reply: "<?= htmlspecialchars($message['admin_reply']) ?>"</p>
                        <?php endif; ?>
                        <button class="delete-button-zalu" onclick="confirmDelete(<?= $message['id'] ?>)">Delete Reply</button>
                        <button class="mark-read-button-neso" onclick="markAsRead(<?= $message['id'] ?>)">Mark as Read</button>
                        <hr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No messages to display.</p>
                <?php endif; ?>
            </div>
        </div>

        <script>
            // Initial number of unread messages (set this dynamically)
            let unreadMessages = <?= $unreadMessages ?>;

            function openChatModal() {
                document.getElementById("chatModal").style.display = "block";
            }

            function closeChatModal() {
                document.getElementById("chatModal").style.display = "none";
            }

            function confirmDelete(messageId) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "Manchester.php?delete_id=" + messageId;
                    }
                });
            }

            function markAsRead(messageId) {
                // Send a request to mark the message as read
                window.location.href = "Manchester.php?mark_read_id=" + messageId;
            }

        </script>

        <div class="user-info">
            <h2><span class="name"><?php echo htmlspecialchars($name); ?></span></h2>
            <br>
            <ul class="nav-links">
                <li><a href="#" class="nav-link" data-page="home">Home</a></li>
                <li><a href="#" class="nav-link" data-page="topup">Top-up</a></li>
                <li><a href="#" class="nav-link" data-page="activate">Activate</a></li>
                <li><a href="#" class="nav-link" data-page="refill">Refill History</a></li>
                <li><a href="#" class="nav-link" data-page="traffic">Traffic History</a></li>
                <li><a href="#" class="nav-link" data-page="subscription">Subscription History</a></li>
                <li><a href="#" class="nav-link" data-page="live">Live Chat</a></li>
                <li><a href="#" class="nav-link" data-page="credentials">Account Credentials</a></li>
            </ul>
        </div>

        <div class="content" id="content">
            <h3>Account Information</h3>
            <table>
                <tr>
                    <td>Account Name</td>
                    <td><?php echo htmlspecialchars($name); ?></td>
                </tr>
                <tr>
                    <td>Account Status</td>
                    <td>Active</td>
                </tr>
                <tr>
                    <td>Connection Status</td>
                    <td>Online</td>
                </tr>
                <tr>
                    <td>Current Package</td>
                    <td>Wireless 25 Mbps @ Kshs 2500</td>
                </tr>
                <tr>
                    <td>Download Rate</td>
                    <td>25 Mbps</td>
                </tr>
                <tr>
                    <td>Upload Rate</td>
                    <td>25 Mbps</td>
                </tr>
                <tr>
                    <td>Account Expiry Date</td>
                    <td>Oct 25, 2024, 8:37 PM</td>
                </tr>
                <tr>
                    <td>Last Online</td>
                    <td>Sep 27, 2024, 3:07 PM</td>
                </tr>
                <tr>
                    <td>Last IP</td>
                    <td>N/A</td>
                </tr>
            </table>
        </div>
    </div>

    <script src="scripts.js"></script>
</body>
</html>
