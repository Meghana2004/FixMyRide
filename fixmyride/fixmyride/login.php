<?php
session_start();
$errors = [];
$username = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty($_POST['username'])) {
        $errors['username'] = "Username is required";
    } else {
        $username = test_input($_POST['username']);
        if (!preg_match("/^[a-zA-Z0-9_]{5,20}$/", $username)) {
            $errors['username'] = "Username must be 5-20 characters and contain only letters, numbers, and underscores";
        }
    }

    // Validate password
    if (empty($_POST['password'])) {
        $errors['password'] = "Password is required";
    } else {
        $password = test_input($_POST['password']);
    }

    // If no errors, check credentials
    if (empty($errors)) {
        // Connect to database
        $conn = new mysqli("localhost", "root", "", "fixmyride");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check user credentials using prepared statement
        $stmt = $conn->prepare("SELECT user_id, password FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($user_id, $hashed_password);

        if ($stmt->num_rows > 0) {
            $stmt->fetch();
            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                header("Location: dashboard.php");
                exit();
            } else {
                $errors['login'] = "Invalid username or password";
            }
        } else {
            $errors['login'] = "Invalid username or password";
        }

        $stmt->close();
        $conn->close();
    }
}

function test_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FixMyRide</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="fixmyride">FixMyRide</div>
    <div class="container">
        <h1>Login</h1>
        <form action="login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
            <span class="error"><?php echo $errors['username'] ?? ''; ?></span><br><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <span class="error"><?php echo $errors['password'] ?? ''; ?></span><br>

            <a href="signup.php">Don't have an account?</a><br><br>

            <input type="submit" value="Login">
            <span class="error"><?php echo $errors['login'] ?? ''; ?></span>
        </form>
    </div>
</body>
</html>
