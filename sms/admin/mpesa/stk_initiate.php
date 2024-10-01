<?php
if(isset($_POST['submit'])){
  
  date_default_timezone_set('Africa/Nairobi');

  $consumerKey = ''*********************';'; // Your app Consumer Key
  $consumerSecret = ''*********************';'; // Your app Consumer Secret

  $BusinessShortCode = ''****'; // Your M-PESA Business Short Code
  $Passkey = '*********************'; // Your M-PESA Passkey

  $PartyA = $_POST['phone']; // Customer's phone number
  $AccountReference = 'Muindi Shop'; // Reference number for the transaction
  $TransactionDesc = 'Test Payment'; // Description of the transaction
  $Amount = $_POST['amount']; // Amount to be transacted

  $Timestamp = date('YmdHis'); // Current timestamp
  $Password = base64_encode($BusinessShortCode.$Passkey.$Timestamp); // Base64 encoded password

  $headers = ['Content-Type:application/json; charset=utf8'];
  $access_token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

  $curl = curl_init($access_token_url);
  curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($curl, CURLOPT_HEADER, FALSE);
  curl_setopt($curl, CURLOPT_USERPWD, $consumerKey.':'.$consumerSecret);
  $result = curl_exec($curl);
  $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  $result = json_decode($result);
  $access_token = $result->access_token;
  curl_close($curl);

  $initiate_url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

  $stkheader = ['Content-Type:application/json','Authorization:Bearer '.$access_token];

  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $initiate_url);
  curl_setopt($curl, CURLOPT_HTTPHEADER, $stkheader);

  $curl_post_data = array(
    'BusinessShortCode' => $BusinessShortCode,
    'Password' => $Password,
    'Timestamp' => $Timestamp,
    'TransactionType' => 'CustomerPayBillOnline',
    'Amount' => $Amount,
    'PartyA' => $PartyA,
    'PartyB' => $BusinessShortCode,
    'PhoneNumber' => $PartyA,
    'CallBackURL' => 'https://morning-basin-87523.herokuapp.com/callback_url.php',
    'AccountReference' => $AccountReference,
    'TransactionDesc' => $TransactionDesc
  );

  $data_string = json_encode($curl_post_data);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
  $curl_response = curl_exec($curl);
  curl_close($curl);

  // Assuming $curl_response contains the JSON response from the transaction
  
  // Decode the JSON response
  $response = json_decode($curl_response, true);

  // Check if the transaction was successful
  if(isset($response['ResponseCode']) && $response['ResponseCode'] === '0') {
    // Redirect to success.html
    header('Location: success.php');
    exit;
  } else {
    // Handle error case if needed
    header("Location: failed.php");
    exit();
}

}
?>
