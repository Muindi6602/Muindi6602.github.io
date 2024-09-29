<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manchester - Signup</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<style>
input[type="email"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}
</style>
<body>
    <div class="container">
        <h1>Manchester</h1>
        <h5 style="color: blue;">Sign Up to get connected</h5>
        <form id="signup-form" method="POST">
            <input type="text" name="name" placeholder="Name" required>
            <input type="text" name="phone_number" placeholder="Phone Number" required>
            <input type="text" name="phone_type" placeholder="Phone Type" required>
            <input type="text" name="mac_address" placeholder="Mac Address" required>
            <input type="email" name="email" placeholder="example@gmail.com" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <div class="g-recaptcha" data-sitekey="6LdjjpQpAAAAAA1UOTIhPXwvALuUkO9DwywxOoh3"></div>
            <br>
            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="index.php">Login</a></p>
    </div>

    <!-- SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script>
        document.getElementById('signup-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            Swal.fire({
                title: "Are you sure?",
                text: "You want to signup with Manchester?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, signup!"
            }).then((result) => {
                if (result.isConfirmed) {
                    // If confirmed, submit the form using AJAX
                    var form = document.getElementById('signup-form');
                    var formData = new FormData(form);

                    fetch('process_signup.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(data => {
                        if (data === "success") {
                            Swal.fire({
                                title: "Success",
                                text: "Manchester is for you",
                                icon: "success"
                            }).then(() => {
                                window.location.href = "index.php"; // Redirect to index.php
                            });
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: data,
                                icon: "error"
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: "Error",
                            text: "There was an error processing your request.",
                            icon: "error"
                        });
                    });
                } else {
                    // If cancelled, redirect back to signup.php
                    window.location.href = "signup.php";
                }
            });
        });
    </script>
</body>
</html>
