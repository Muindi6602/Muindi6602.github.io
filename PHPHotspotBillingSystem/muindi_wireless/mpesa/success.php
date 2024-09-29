<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manchester | Transaction</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #2c3e50;
            font-family: Arial, sans-serif;
        }

        .container {
            text-align: center;
        }

        .message-box {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 300px;
            margin: auto;
        }

        .checkmark {
            font-size: 48px;
            color: #2ecc71;
        }

        h1 {
            margin: 20px 0 10px;
            font-size: 24px;
            color: #333333;
        }

        p {
            color: #666666;
            margin-bottom: 20px;
        }

        button {
            background-color: #2ecc71;
            border: none;
            color: #ffffff;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }
        
        button:hover {
            background-color: #27ae60;
        }

        input {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        input:focus {
            outline: none;
            border-color: #27ae60;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="message-box">
            <div class="checkmark">&#10004;</div>
            <h1>Almost there</h1>
           <p>Enter Mpesa Pin to complete the transaction.</p>
           <button id="okButton">To portal</button>
           <h4>If you don't get connection automatically, enter the Mpesa transaction code below to activate manually.</h4>
           
           <br>
           <label for="transactionCode">Enter M-PESA Transaction Code:</label><br><br>
           <input type="text" id="transactionCode" name="transactionCode" oninput="this.value = this.value.toUpperCase();" placeholder="e.g. ABC1234567"><br><br>
            
           <button id="actionButton">Activate</button>

           <script>
               document.getElementById('actionButton').onclick = function() {
                   const button = this;
                   const originalText = 'Activate';
                   const checkingText = 'Checking';
                   const originalColor = button.style.backgroundColor;
                   let dotCount = 1;

                   button.disabled = true; // Disable the button during the animation
                   button.style.backgroundColor = 'blue'; // Change color to blue

                   // Create an interval to update the button text
                   const interval = setInterval(() => {
                       button.textContent = checkingText + '.'.repeat(dotCount);
                       dotCount = (dotCount % 3) + 1; // Cycle through 1, 2, 3 dots
                   }, 500); // Update every 500ms

                   // After 6 seconds, stop the animation and reset the button text and color
                   setTimeout(() => {
                       clearInterval(interval);
                       button.textContent = originalText;
                       button.style.backgroundColor = originalColor; // Revert color to original
                       button.disabled = false; // Re-enable the button
                   }, 10000); // Total duration of 10 seconds
               };
           </script>
        </div>
    </div>

    <script>
        document.getElementById('okButton').addEventListener('click', function() {
            window.location.href = '/muindi_wireless/manchester.php';
        });
    </script>

</body>
</html>
