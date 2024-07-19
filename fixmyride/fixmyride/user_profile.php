<?php
session_start();

// Connect to the database
$conn = new mysqli("localhost", "root", "", "fixmyride");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user details based on the email or username stored in session
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $sql = "SELECT user_id, username, email FROM users WHERE email = ?";
} elseif (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $sql = "SELECT user_id, username, email FROM users WHERE username = ?";
} else {
    echo "User details not found";
    exit();
}

// Prepare and bind the SQL query
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email_username);

// Set the parameter values and execute the query
if (isset($email)) {
    $email_username = $email;
} elseif (isset($username)) {
    $email_username = $username;
}
$stmt->execute();
$result = $stmt->get_result();

// Close prepared statement (will close automatically at the end of script execution) 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        table {
            border-collapse: collapse;
            width: 50%;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        input[type="password"],
        input[type="text"],
        input[type="email"],
        button[type="submit"],
        .admin-button {
            padding: 10px;
            margin: 5px 0;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button[type="submit"] {
            background-color: orange;
            color: white;
        }
        .admin-button {
            background-color: orange;
            padding: 10px;
            margin: 5px 0;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left:620px;
            color: white;
        }
        .admin-button:hover {
            background-color: #FF8C00;
      }
      .h1{
        text-align: center;
      }
      </style>

</head>
<body>
    <h1>User Profile</h1>
    <form action="update_profile.php" method="post">
        <table>
            <tr>
                <th>Field</th>
                <th>Value</th>
            </tr>
            <?php
            // Fetch user details from the database and display them
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<input type='hidden' name='user_id' value='" . htmlspecialchars($row["user_id"]) . "'>";
                    echo "<tr>";
                    echo "<td>User ID</td><td>" . htmlspecialchars($row["user_id"]) . "</td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td>Username</td><td><input type='text' name='username' value='" . htmlspecialchars($row["username"]) . "'></td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td>Email</td><td><input type='email' name='email' value='" . htmlspecialchars($row["email"]) . "'></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No user data found</td></tr>";
            }
            ?>
            <tr>
                <td>Current Password</td>
                <td><input type="password" name="current_password"></td>
            </tr>
            <tr>
                <td>New Password</td>
                <td><input type="password" name="new_password"></td>
            </tr>
            <tr>
                <td>Confirm New Password</td>
                <td><input type="password" name="confirm_password"></td>
            </tr>
            <tr>
                <td colspan="2"><button type="submit">Update Profile</button></td>
            </tr>
        </table>
    </form>
    <button type="button" class="admin-button" onclick="window.location.href = 'dashboard.php';">Go to Dashboard</button>
</body>
</html>
