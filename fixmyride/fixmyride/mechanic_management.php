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

// Check if district and service number are provided in the query string
if (isset($_GET['district']) && isset($_GET['service_number'])) {
    $district = $_GET['district'];
    $service_number = $_GET['service_number'];

    // Query to retrieve mechanics based on the district
    $sql = "SELECT * FROM mechanics WHERE district = '$district'";
    $result = $conn->query($sql);

    if (!$result) {
        die("Error retrieving mechanics: " . $conn->error);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mechanic Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin-left: 600px;
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
        }
        .fixmyride {
            font-size: 35px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .select-button {
            background-color: orange;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            margin-right: 100px;
        }
        .select-button:hover {
            background-color: #e69500;
        }
        h1 {
            text-align: center;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-right: 500px;
        
        }
            </style>

</head>
<body>
    <div class="container">
        <div class="fixmyride">FixMyRide</div>
        <h1>Mechanic Management - <?php echo htmlspecialchars($district); ?></h1>
        <table>
            <thead>
                <tr>
                    <th>Mechanic ID</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Area</th>
                    <th>District</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["contact"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["area"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["district"]) . "</td>";
                        echo "<td>
                                <button class='select-button' onclick='assignMechanic(" . $row["id"] . ", \"" . $service_number . "\")'>Select</button>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No mechanics available in this district</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function assignMechanic(mechanicId, serviceNumber) {
            if (confirm("Mechanic is assigned successfully")) {
                window.location.href = "accepted.php?mechanic_id=" + mechanicId + "&service_number=" + encodeURIComponent(serviceNumber);
            }
        }
    </script>
</body>
<a href="admin_home.php" class="select-button">Home</a>
</html>

<?php
} else {
    // If district or service number is not provided in the query string, display an error message
    echo "District or Service Number not provided";
}
$conn->close();
?>
