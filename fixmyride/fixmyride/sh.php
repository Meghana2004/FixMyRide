<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service History</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f2f2f2;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

.container {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    width: 100%;
    width: 7000px;
    margin-right: 20px;
    margin-left:100px;
}

h1 {
    text-align: center;
    margin-bottom: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
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
    background-color: #f5f5f5;
}

.home-button {
    display: block;
    width: 140px;
    padding: 10px;
    margin: 20px auto 0;
    text-align: center;
    background-color: #ff9800;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.home-button:hover {
    background-color: #fb8c00;
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Service History</h1>
        <?php
        session_start();

        // Check if the user is logged in
        if (!isset($_SESSION['user_id'])) {
            // Redirect to the login page or handle unauthorized access
            header("Location: login.php");
            exit();
        }

        // Get the user ID from the session
        $user_id = $_SESSION['user_id'];

        // Connect to the database
        $conn = new mysqli("localhost", "root", "", "fixmyride");

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch user service details with corresponding user details
        $sql = "SELECT us.id, us.service_number, us.vehicle_type, us.vehicle_brand, us.vehicle_model, 
                    us.vehicle_reg_no, us.service_date, us.service_type, us.other_service, us.contact_no, 
                    us.area, us.district, u.username, u.email
                FROM users_service us
                INNER JOIN users u ON us.user_id = u.user_id
                WHERE us.user_id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>ID</th><th>Service Number</th><th>Vehicle Type</th><th>Vehicle Brand</th>
                    <th>Vehicle Model</th><th>Vehicle Reg No</th><th>Service Date</th><th>Service Type</th>
                    <th>Other Service</th><th>Contact No</th><th>Area</th><th>District</th><th>Username</th>
                    <th>Email</th></tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row["id"]."</td>";
                echo "<td>".$row["service_number"]."</td>";
                echo "<td>".$row["vehicle_type"]."</td>";
                echo "<td>".$row["vehicle_brand"]."</td>";
                echo "<td>".$row["vehicle_model"]."</td>";
                echo "<td>".$row["vehicle_reg_no"]."</td>";
                echo "<td>".$row["service_date"]."</td>";
                echo "<td>".$row["service_type"]."</td>";
                echo "<td>".$row["other_service"]."</td>";
                echo "<td>".$row["contact_no"]."</td>";
                echo "<td>".$row["area"]."</td>";
                echo "<td>".$row["district"]."</td>";
                echo "<td>".$row["username"]."</td>";
                echo "<td>".$row["email"]."</td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "No records found";
        }

        // Close prepared statement and database connection
        $stmt->close();
        $conn->close();
        ?>
        <a href="dashboard.php" class="home-button">Home</a>
    </div>
</body>
</html>
