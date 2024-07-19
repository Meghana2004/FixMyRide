<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fixmyride";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $new_username = test_input($_POST['username']);
    $new_email = test_input($_POST['email']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch the current password from the database
    $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $stmt->bind_result($db_password);
    $stmt->fetch();
    $stmt->close();

    if (!password_verify($current_password, $db_password)) {
        echo "<script>alert('Current password is incorrect'); window.location.href='user_profile.php';</script>";
    } elseif ($new_password !== $confirm_password) {
        echo "<script>alert('New passwords do not match'); window.location.href='user_profile.php';</script>";
    } else {
        // Update user details
        if (!empty($new_password)) {
            $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET username=?, email=?, password=? WHERE user_id=?");
            $stmt->bind_param("ssss", $new_username, $new_email, $hashed_new_password, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET username=?, email=? WHERE user_id=?");
            $stmt->bind_param("sss", $new_username, $new_email, $user_id);
        }

        if ($stmt->execute()) {
            echo "<script>alert('Profile updated successfully!'); window.location.href='user_profile.php';</script>";
        } else {
            echo "<script>alert('Error updating profile: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    }
}

$conn->close();

function test_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
?>
