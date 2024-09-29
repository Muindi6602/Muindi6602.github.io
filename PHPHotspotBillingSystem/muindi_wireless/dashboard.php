<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include 'database.php';

if (!isset($pdo) || !$pdo) {
    die('Database connection failed');
}

$user_id = $_SESSION['user_id'];

try {
    // Prepare and execute the SQL query
    $stmt = $pdo->prepare("SELECT name, phone_type FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    
    // Fetch the results
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $name = $result['name'];
    $phone_type = $result['phone_type'];
    
    $pdo = null;
    
} catch (PDOException $e) {
    die('Query failed: ' . htmlspecialchars($e->getMessage()));
}

// packages
$host = 'localhost';
$dbname = 'Manchester';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Fetch vouchers from the database
$stmt = $pdo->query("SELECT code, price FROM vouchers");
$vouchers = $stmt->fetchAll(PDO::FETCH_ASSOC);



// Fetch the user's name from the 'users' table using the session ID
$user_id = $_SESSION['user_id'];
$sql = "SELECT name FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$user_name = $user['name'];

// Handle message submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_message'])) {
    $user_message = trim($_POST['user_message']);
    
    if (!empty($user_message)) {
        // Insert the message into the 'messages' table
        $sql = "INSERT INTO messages (name, user_message) VALUES (:name, :user_message)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'name' => $user_name,
            'user_message' => $user_message
        ]);
        $success = "Your message has been sent to the admin!";
    } else {
        $error = "Message cannot be empty!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muindi | Payment</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="adstyles.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            text-align: center;
            background-color: pink;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #343a40;
            font-size: 2em;
        }
        h2 {
            color: #495057;
            margin-top: 20px;
        }
        .packages {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 20px;
        }
        .package-btn {
            background-color: #ffc107;
            border: none;
            padding: 15px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            color: #212529;
            flex-grow: 1;
            transition: background-color 0.3s ease;
        }
        .package-btn:hover {
            background-color: #e0a800;
        }
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: #ffffff;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            animation: fadeIn 0.3s ease-in-out;
        }
        .modal-content h3 {
            margin-bottom: 20px;
            color: #343a40;
        }
        .modal-content input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        .modal-content button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }
        .modal-content button:hover {
            background-color: #218838;
        }
        .close {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        .close:hover,
        .close:focus {
            color: #000000;
            text-decoration: none;
        }
        .logout-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .logout-btn:hover {
            background-color: #c82333;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        
            button[type="submit2"]:hover {
                background-color: #45a049;
            }

 /* messages */
.modal_fancy {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1000; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */
}

/* Modal content styling */
/* Modal background styling */
.modal_fancy {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgba(0, 0, 0, 0.7); /* Black w/ opacity */
    backdrop-filter: blur(5px); /* Add blur effect */
}

/* Modal content styling */
.modal_content_fancy {
    background-color: #ffffff;
    margin: auto;
    padding: 20px;
    border: none; /* Remove border */
    width: 70%;
    max-width: 600px; /* Updated max width */
    border-radius: 15px; /* More rounded corners */
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2); /* Soft shadow for depth */
    position: relative;
    top: 50%;
    transform: translateY(-50%);
    animation: scaleIn 0.3s ease forwards; /* Animation for opening */
}

/* Close button styling */
.close_fancy {
    color: #333;
    float: right;
    font-size: 28px;
    font-weight: bold;
    transition: color 0.3s; /* Smooth color change */
}

.close_fancy:hover,
.close_fancy:focus {
    color: #ff4d4d; /* Change color on hover */
    cursor: pointer;
}

/* Feedback message styling */
.feedback_fancy {
    font-size: 14px;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 5px;
    transition: opacity 0.3s; /* Fade in/out effect */
}

.feedback_fancy.success {
    background-color: #2ecc71; /* Green for success */
    color: white;
}

.feedback_fancy.error {
    background-color: #e74c3c; /* Red for error */
    color: white;
}

/* Button styling */
.button_fancy_submit {
    background-color: #3498db; /* Primary button color */
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s; /* Smooth transitions */
}

.button_fancy_submit:hover {
    background-color: #2980b9; /* Darker shade on hover */
    transform: translateY(-2px); /* Slight lift effect */
}

/* Keyframes for opening animation */
@keyframes scaleIn {
    from {
        transform: translateY(-50%) scale(0.5);
        opacity: 0;
    }
    to {
        transform: translateY(-50%) scale(1);
        opacity: 1;
    }
}

/* Additional styles for textarea and button */
.message_input_fancy {
    width: 100%; /* Full width of the modal */
    border-radius: 5px;
    border: 1px solid #ccc;
    padding: 10px;
    box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.1); /* Inner shadow */
    transition: border-color 0.3s; /* Smooth border color transition */
}

.message_input_fancy:focus {
    border-color: #3498db; /* Change border color on focus */
    outline: none; /* Remove default outline */
}

    </style>

</head>
<body>
    <div class="container">
        <h1>Manchester</h1>
        <br>
        Welcome @<span style="font-weight: bold;"><?php echo htmlspecialchars($name); ?></span> 
        <br><br>
        Phone type: <span style="font-style: italic;"><?php echo htmlspecialchars($phone_type); ?></span>

        <h2>Select Package</h2> (All packages come with 25Mbps)
        
        <br><br>
        <div class="packages">
        <?php foreach ($vouchers as $voucher): ?>
            <button class="package-btn" onclick="openVoucherModal('<?= htmlspecialchars($voucher['code']) ?>', <?= htmlspecialchars($voucher['price']) ?>)">
                <?= htmlspecialchars($voucher['code']) ?> - Ksh <?= htmlspecialchars($voucher['price']) ?>
            </button>
        <?php endforeach; ?>
    </div>

    <!-- Modal Structure -->
    <div id="voucher-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h4>Manchester</h4>

            <h3 id="voucher-title"></h3>
            <form action="mpesa/stk_initiate.php" method="POST">
                <input type="hidden" name="voucher_price" id="voucher_price">
                <label for="phone_number">Mpesa Number</label>
                <input type="text" name="phone_number" id="phone_number" required placeholder="254........."> <br><br>
                <button type="submit2" name="submit">Purchase</button>
            </form>
        </div>
    </div>

        <br><br><br>
        <a href="logout.php" class="logout-btn">Logout</a>
        <br><br>
        <p>Already connected? Check <a href="manchester.php">portal</a></p>
     
</div><br>
    </div>

    <script>
        // Open the modal and dynamically set the title and price
        function openVoucherModal(packageName, packagePrice) {
            document.getElementById("voucher-title").innerText = packageName;
            document.getElementById("voucher_price").value = packagePrice;
            document.getElementById("voucher-modal").style.display = "flex";
            document.getElementById("phone_number").focus(); // Focus on phone input when modal opens
        }

        // Close the modal
        function closeModal() {
            document.getElementById("voucher-modal").style.display = "none";
        }

        // Close the modal when pressing 'Escape'
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });

        // Ensure only numeric input for the phone number
        document.addEventListener('DOMContentLoaded', function() {
            const phoneInput = document.getElementById('phone_number');
            if (phoneInput) {
                phoneInput.addEventListener('input', function(e) {
                    this.value = this.value.replace(/[^0-9]/g, ''); // Restrict to digits only
                });
            }
        });
    </script>





<!-- Live Chat Ad -->
<div class="live-chat-ad" id="liveChatAd">
    <div class="chat-icon">
        <!-- Font Awesome Chat Icon -->
        <i class="fas fa-comments"></i>
    </div>
    <div class="chat-text">
        <h2>Manchester</h2><br><br>
        <p>Do you have some Complaints?<br> Leave a message here</p>
    </div><br><br>
    <!-- Button to open the modal -->
    <button type="button" class="button_fancy_submit" id="openModalButton">Message</button>

    <button class="close-button" id="closeButton">✖</button>
</div>

<!-- Modal Structure -->
<div id="messageModal" class="modal_fancy">
    <div class="modal_content_fancy">
        <span class="close_fancy">&times;</span>
        <div class="header_fancy">Message Admin</div>

        <!-- Display success or error message -->
        <div id="feedback" class="feedback_fancy"></div>

        <form id="messageForm">
            <textarea name="user_message" class="message_input_fancy" rows="5" placeholder="Enter your message to admin..." required></textarea>
            <button type="submit" class="button_fancy_submit">Send Message</button> <!-- Moved below the textarea -->
        </form>
    </div>
</div>

<script>
    // Function to display the chat ad
    function showAd() {
        document.getElementById("liveChatAd").style.display = "block";
    }

    // Function to hide the chat ad
    function hideAd() {
        document.getElementById("liveChatAd").style.display = "none";
    }

    // Reappear the ad every 5 seconds
    setInterval(function() {
        showAd();
    }, 5000); // Every 5 seconds

    // Close the ad but it will reappear after 5 seconds
    document.getElementById("closeButton").onclick = function() {
        hideAd();
    }

    // Modal-related functionality
    const modal = document.getElementById("messageModal");
    const openModalButton = document.getElementById("openModalButton");
    const closeModalButton = document.querySelector(".close_fancy");
    const messageForm = document.getElementById("messageForm");
    const feedback = document.getElementById("feedback");

    // Open the modal when the button is clicked
    openModalButton.onclick = function() {
        modal.style.display = "block";
    }

    // Close the modal when "X" is clicked
    closeModalButton.onclick = function() {
        modal.style.display = "none";
    }

    // Close the modal when clicking outside of the modal content
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Handle form submission
    messageForm.onsubmit = function(event) {
        const userMessage = messageForm.user_message.value.trim();

        // Check if the textarea is empty
        if (!userMessage) {
            Swal.fire({
                icon: 'warning',
                title: 'Warning!',
                text: 'Please enter a message before sending.',
                confirmButtonText: 'OK'
            });
            return; // Prevent form submission
        }

        event.preventDefault(); // Prevent form submission

        // Send the message using AJAX
        const formData = new FormData(messageForm);

        fetch('dashboard.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.text())
        .then(data => {

            // Show success message with SweetAlert
            Swal.fire({
                icon: 'success',
                title: 'Message Sent!',
                html: 'You will receive a reply soon in the <a href="manchester.php" target="_blank">portal</a>.',
                confirmButtonText: 'OK'
            });

            // Clear the textarea
            messageForm.reset();
            feedback.classList.remove('error');
            feedback.classList.add('success');
            feedback.textContent = 'Message sent successfully!';
            setTimeout(() => {
                feedback.textContent = '';
            }, 3000); // Clear feedback after 3 seconds
        })
        .catch(error => {
            // Handle errors
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'There was an error sending your message.',
                confirmButtonText: 'OK'
            });
            feedback.classList.remove('success');
            feedback.classList.add('error');
            feedback.textContent = 'Failed to send message!';
            setTimeout(() => {
                feedback.textContent = '';
            }, 3000); // Clear feedback after 3 seconds
        });
    }
</script>



</body>
</html>
