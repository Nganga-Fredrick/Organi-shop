<?php

include __DIR__ . './config/config.php';





    if (isset($_POST['phone'])) {
        $phone = $_POST['phone'];



        $consumerKey = "9As6JThjDUJaZ9GILIBMzyMJepqeaSLL"; //Fill with your app Consumer Key
        $consumerSecret = "tK6W6JiyHjHlSeMQ"; //Fill with your app Consumer Secret
        //ACCESS TOKEN URL
        $access_token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        $headers = ['Content-Type:application/json; charset=utf8'];
        $curl = curl_init($access_token_url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        curl_setopt($curl, CURLOPT_USERPWD, $consumerKey . ':' . $consumerSecret);
        $result = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $result = json_decode($result);


        // ASSIGN ACCESS TOKEN TO A VARIABLE
        $access_token = $result->access_token;
        curl_close($curl);


        date_default_timezone_set('Africa/Nairobi');
        $processrequestUrl = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
        $callbackurl = 'https://haofindertest.great-site.net/mpesa/callback.php';
        $passkey = "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919";
        $BusinessShortCode = '174379';
        $Timestamp = date('YmdHis');
        // ENCRIPT  DATA TO GET PASSWORD
        $Password = base64_encode($BusinessShortCode . $passkey . $Timestamp);
        $money = '30';
        $PartyA = $phone;
        $PartyB = '254798222717';
        $AccountReference = 'Organi Shop';
        $TransactionDesc = 'Order confirmation';
        $Amount = $money;
        $stkpushheader = ['Content-Type:application/json', 'Authorization:Bearer ' . $access_token];

        //INITIATE CURL
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $processrequestUrl);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $stkpushheader); //setting custom header
        $curl_post_data = array(
            //Fill in the request parameters with valid values
            'BusinessShortCode' => $BusinessShortCode,
            'Password' => $Password,
            'Timestamp' => $Timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $Amount,
            'PartyA' => $PartyA,
            'PartyB' => $BusinessShortCode,
            'PhoneNumber' => $PartyA,
            'CallBackURL' => $callbackurl,
            'AccountReference' => $AccountReference,
            'TransactionDesc' => $TransactionDesc
        );

        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        $curl_response = curl_exec($curl);



        $ResponseCode = json_decode($curl_response);

        if (isset($ResponseCode->errorCode)) {
            if ($ResponseCode->errorCode == "400.002.02") {
                $alert_error[] = "Invalid PhoneNumber";
            }
            if ($ResponseCode->errorCode == "500.001.1001") {
                $alert_error[] = "Unable to lock subscriber, a transaction is already in process for the current subscriber";
            }
            if ($ResponseCode->errorCode == "1037") {
                $alert_info[] = "Mobile phone is offline";
            }
            if ($ResponseCode->errorCode == "1025") {
                $alert_info[] = "Mpesa System error. Try again later";
            }
        } elseif (isset($ResponseCode->ResponseCode)) {
            if ($ResponseCode->ResponseCode == "0") {

                $alert_success[] = "Success. Request accepted for processing";

            // Get the values from PHP variables
            $transactionId = '';
$customerId = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;
$cashierId = '';
$timestamp = time();
$totalPrice = $money; // Assuming $money variable contains the total price
$description = 'topup';
$phoneNumber = $phone; // Assuming $phone variable already contains the phone number
$status = 'complete';

// Create the full SQL INSERT query
$sql = "INSERT INTO orders (created_at, order_total, order_notes,) values( now(), $money, 'order', $phoneNumber  )";

if ($conn->query($sql) === TRUE) {
    echo "Record inserted successfully!";
    //header ('location: student\index.html');
} else {
   // echo "Error: " . $conn->error;
}


            // Close the statement and database connection
           
            $conn->close();


        }

               
            }
        }

     
    


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mpesa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" />
    <link rel="stylesheet" href="" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" />
<style>

@import url('https://fonts.googleapis.com/css2?family=Roboto&display=swap');

body{
	background-color: #EEEFF3;
	font-family: 'Roboto', sans-serif;
}

.card{
	width: 300px;
    height: 370px;
	border: none;
	border-radius: 10px;
	background: #4E68C7;
}
p.top{
	font-size: 14px;
}
.discount{
	background-color: #1BC5DF;
	border: none;
	border-top-left-radius: 25px;
	border-bottom-left-radius: 25px;
	padding: 5px 15px;
	transform: translateX(24px);
	height: 35px;
}
.discount span{
	font-size: 15px;
}
h2{
	letter-spacing: 2px;
}
.fa-euro-sign{
	font-size: 27px;
	color: #B3C4FA;
}
.card-content p{
	line-height: 18px;
	font-size: 11px;
	color: #abbef6;
}
.btn-primary{
	border: none;
	border-radius: 6px;
	background-color: #647EDF;
	padding-top: 0;
	height: 46px;
}
.btn-primary span{
	font-size: 13px;
	color: #D1E2FF;
	margin-right: 10px;
}
.fa-arrow-right{
	font-size: 12px;
	color: #D1E2FF;
}
.btn-primary:hover,
.btn-primary:focus
{
	background-color: #647EDF;
	box-shadow: none;
}
input[type="tel"], input[type="number"] {
    color: #D1E2FF;
  border: none;
  border-radius: 6px;
  background-color: #647EDF;
  padding-top: 0;
  height: 46px;
}

    </style>
</head>


<body>
    <!-- BEGINS THE USER PROFILE -->

    

    <form action="" method="post">
    <div class="wrapper">
        <div class="container d-flex justify-content-center">
            <?php if (isset($alert_error)) {
    echo "Alert Error: " . implode(", ", $alert_error);
}
if (isset($alert_info)) {
    echo "Alert Info: " . implode(", ", $alert_info);
}
if (isset($alert_success)) {
    echo "Alert Success: " . implode(", ", $alert_success);
}  ?>
            <div class="card mt-5 p-4 text-white">
                <p class="top mb-6">Amount to TopUP</p>
                <div class="d-flex flex-row justify-content-between text-align-center xyz mb-3">
                    <h2><i class="fas fa-ksh mb-3"></i><span></span></h2>
                    <div class="discount"><span>2% extra</span></div>
                </div>
                <div class="card-content mt-4 mb-4">
                    <p>Once you complete the payment, you can order any meal.</p>
                </div>
                <div class="mt-2 btn-primary">
                    <input type="text" name="price" placeholder="Amount" required>
                </div>
                <div class="mt-2 btn-primary">
                    <input type="text" name="phone" placeholder="Phone" required>
                </div>
                <div class="mt-2">
                    <button class="btn btn-block btn-lg btn-primary" type="submit"><span>Make payment</span><i class="fas fa-arrow-right"></i></button>
                </div>
            </div>
        </div>
    </div>
</form>

    <!-- SweetAlert CDN js Link and PhP file -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <!-- Include jQuery library -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include Bootstrap CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.5.0/css/bootstrap.min.css">



</body>

</html>