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

// Query to retrieve data from users_service table
$sql = "SELECT * FROM users_service";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Requests</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin: 20px;
        }
        table {
            border-collapse: collapse;
            width: 80%;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .container {
            width: 100%;
            max-width: 1200px;
            margin-right: 100px;
        }
        .fixmyride {
            font-size: 35px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .get-assigned-button {
            padding: 10px 20px;
            margin: 0;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .select-button {
            background-color: orange;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="fixmyride">FixMyRide</div>
        <h1>Service Requests</h1>
        <table>
            <thead>
                <tr>
                    <th>Service Number</th>
                    <th>Vehicle Type</th>
                    <th>Vehicle Brand</th>
                    <th>Vehicle Model</th>
                    <th>Vehicle Registration Number</th>
                    <th>Service Date</th>
                    <th>Service Type</th>
                    <th>Other Service</th>
                    <th>Contact Number</th>
                    <th>Area</th>
                    <th>District</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["service_number"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["vehicle_type"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["vehicle_brand"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["vehicle_model"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["vehicle_reg_no"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["service_date"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["service_type"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["other_service"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["contact_no"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["area"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["district"]) . "</td>";
                        echo "<td>
                                <form method='get' action='mechanic_management.php'>
                                    <input type='hidden' name='district' value='" . htmlspecialchars($row["district"]) . "'>
                                    <input type='hidden' name='service_number' value='" . htmlspecialchars($row["service_number"]) . "'>
                                    <input type='submit' value='Get Assigned' class='get-assigned-button'>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='12'>No service requests found</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
    <a href="admin_home.php" class="select-button">Home</a>
</body>
</html>
