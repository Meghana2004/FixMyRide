<?php
// Database credentials
$servername = "localhost";
$username = "root";
$password = ""; // Update this with your actual password
$dbname = "fixmyride";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if mechanic ID and service number are provided in the query string
if (isset($_GET['mechanic_id']) && isset($_GET['service_number'])) {
    $mechanic_id = $_GET['mechanic_id'];
    $service_number = $_GET['service_number'];

    // Query to retrieve mechanic details
    $sql = "SELECT * FROM mechanics WHERE id = '$mechanic_id'";
    $result = $conn->query($sql);

    if (!$result) {
        die("Error retrieving mechanic details: " . $conn->error);
    }

    $mechanic = $result->fetch_assoc();

    // Query to retrieve service request details
    $sql2 = "SELECT * FROM users_service WHERE service_number = '$service_number'";
    $result2 = $conn->query($sql2);

    if (!$result2) {
        die("Error retrieving service details: " . $conn->error);
    }

    $service = $result2->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignment Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin: 20px;
        }
        .container {
            width: 100%;
            max-width: 600px;
            text-align: center;
        }
        .fixmyride {
            font-size: 35px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .details {
            border: 1px solid #ddd;
            padding: 20px;
            margin-top: 20px;
            background-color: #f9f9f9;
        }
        .details h2 {
            margin-top: 0;
        }
        .done-button {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="fixmyride">FixMyRide</div>
        <h1>Assignment Confirmation</h1>
        <div class="details">
            <h2>Service Details</h2>
            <p><strong>Service Number:</strong> <?php echo htmlspecialchars($service['service_number']); ?></p>
            <p><strong>Vehicle Type:</strong> <?php echo htmlspecialchars($service['vehicle_type']); ?></p>
            <p><strong>Vehicle Brand:</strong> <?php echo htmlspecialchars($service['vehicle_brand']); ?></p>
            <p><strong>Vehicle Model:</strong> <?php echo htmlspecialchars($service['vehicle_model']); ?></p>
            <p><strong>Vehicle Registration Number:</strong> <?php echo htmlspecialchars($service['vehicle_reg_no']); ?></p>
            <p><strong>Service Date:</strong> <?php echo htmlspecialchars($service['service_date']); ?></p>
            <p><strong>Service Type:</strong> <?php echo htmlspecialchars($service['service_type']); ?></p>
            <p><strong>Other Service:</strong> <?php echo htmlspecialchars($service['other_service']); ?></p>
            <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($service['contact_no']); ?></p>
            <p><strong>Area:</strong> <?php echo htmlspecialchars($service['area']); ?></p>
            <p><strong>District:</strong> <?php echo htmlspecialchars($service['district']); ?></p>
        </div>
        <div class="details">
            <h2>Mechanic Details</h2>
            <p><strong>Mechanic ID:</strong> <?php echo htmlspecialchars($mechanic['id']); ?></p>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($mechanic['name']); ?></p>
            <p><strong>Contact:</strong> <?php echo htmlspecialchars($mechanic['contact']); ?></p>
            <p><strong>Area:</strong> <?php echo htmlspecialchars($mechanic['area']); ?></p>
            <p><strong>District:</strong> <?php echo htmlspecialchars($mechanic['district']); ?></p>
        </div>
        <form action="admin_home.php" method="get" class="done-button">
            <button type="submit">Done</button>
        </form>
    </div>
</body>
</html>

<?php
} else {
    // If mechanic ID or service number is not provided in the query string, display an error message
    echo "Mechanic ID or Service Number not provided";
}
$conn->close();
?>
