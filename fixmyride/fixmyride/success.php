<?php
// Retrieve the service number from the URL parameter
$serviceNumber = isset($_GET['serviceNumber']) ? htmlspecialchars($_GET['serviceNumber']) : "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }
        .container {
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 40%;
            border-radius: 10px;
            text-align: center;
        }
        .container h1 {
            color: #e8491d;
        }
        .container p {
            font-size: 1.2em;
        }
        .button-container {
            margin-top: 20px;
        }
        .button-container a {
            text-decoration: none;
            padding: 10px 20px;
            background-color: #e8491d;
            color: white;
            border-radius: 5px;
            font-size: 1em;
        }
        .button-container a:hover {
            background-color: #ff7518;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Service Request Submitted Successfully!</h1>
        <p>Your service number is: <strong><?php echo $serviceNumber; ?></strong></p>
        <p>Thank you for choosing FixMyRide. We will contact you shortly.</p>
        <div class="button-container">
            <a href="dashboard.php">Home</a>
        </div>
    </div>
</body>
</html>
