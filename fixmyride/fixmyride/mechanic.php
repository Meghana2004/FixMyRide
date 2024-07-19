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

// Function to sanitize inputs
function sanitizeInput($conn, $input) {
    return $conn->real_escape_string(trim($input));
}

// Add a new mechanic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_mechanic"])) {
    $mechanic_name = sanitizeInput($conn, $_POST["mechanic_name"]);
    $mechanic_contact = sanitizeInput($conn, $_POST["mechanic_contact"]);
    $mechanic_area = sanitizeInput($conn, $_POST["mechanic_area"]);
    $mechanic_district = sanitizeInput($conn, $_POST["mechanic_district"]);

    $sql = "INSERT INTO mechanics (name, contact, area, district) VALUES ('$mechanic_name', '$mechanic_contact', '$mechanic_area', '$mechanic_district')";
    if ($conn->query($sql) === TRUE) {
        echo "New mechanic added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Remove a mechanic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["remove_mechanic"])) {
    $mechanic_id = sanitizeInput($conn, $_POST["mechanic_id"]);

    $sql = "DELETE FROM mechanics WHERE id='$mechanic_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Mechanic removed successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Query to retrieve added mechanics
$sql = "SELECT * FROM mechanics";
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
        }
        .fixmyride {
            font-size: 35px;
            font-weight: bold;
            margin-bottom: 20px;
            margin-right:1200px;
        }
        .select-button {
            background-color: orange;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }
        .select-button:hover {
            padding: 10px;
            background-color: #e69500;
        }
        h1 {
            text-align: center;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
    </style>

</head>
<body>
    <div class="container">
        <div class="fixmyride">FixMyRide</div>
        
        <div class="add-mechanic">
            <i class="fas fa-plus-circle add-mechanic-icon"></i>
            <form method="post" action="">
                <label for="mechanic_name">Name:</label>
                <input type="text" id="mechanic_name" name="mechanic_name" placeholder="Mechanic Name" required>
                <label for="mechanic_contact">Contact:</label>
                <input type="text" id="mechanic_contact" name="mechanic_contact" placeholder="Contact Number" required>
                <label for="mechanic_area">Area:</label>
                <input type="text" id="mechanic_area" name="mechanic_area" placeholder="Area" required>
                <label for="mechanic_district">District:</label>
                <input type="text" id="mechanic_district" name="mechanic_district" placeholder="District" required>
                <input type="submit" name="add_mechanic" value="Add Mechanic">
            </form>
        </div>
        
        <form method="post" action="" class="add-mechanic">
            <label for="mechanic_id">Mechanic ID to Remove:</label>
            <input type="text" id="mechanic_id" name="mechanic_id" placeholder="Mechanic ID" required>
            <input type="submit" name="remove_mechanic" value="Remove Mechanic">
        </form>

        <table>
            <thead>
                <tr>
                    <th>Mechanic ID</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Area</th>
                    <th>District</th>
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
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No mechanics added yet</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <a href="admin_home.php" class="select-button">Home</a>
</body>
</html>
<?php
$conn->close();
?>
