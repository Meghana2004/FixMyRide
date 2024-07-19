<?php
session_start();
$errors = [];
$username = $email = $password = $confirm_password = "";

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

    // Validate email
    if (empty($_POST['email'])) {
        $errors['email'] = "Email is required";
    } else {
        $email = test_input($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format";
        }
    }

    // Validate password
    if (empty($_POST['password'])) {
        $errors['password'] = "Password is required";
    } else {
        $password = test_input($_POST['password']);
        if (strlen($password) < 6) {
            $errors['password'] = "Password must be at least 6 characters long";
        }
    }

    // Validate confirm password
    if (empty($_POST['confirm_password'])) {
        $errors['confirm_password'] = "Confirm password is required";
    } else {
        $confirm_password = test_input($_POST['confirm_password']);
        if ($password !== $confirm_password) {
            $errors['confirm_password'] = "Passwords do not match";
        }
    }

    // If no errors, proceed to check email uniqueness and insert user
    if (empty($errors)) {
        // Connect to database
        $conn = new mysqli("localhost", "root", "", "fixmyride");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors['email'] = "User already exists with this email";
        } else {
            // Generate unique user_id
            do {
                $user_id = generateUserId();
                $stmt = $conn->prepare("SELECT id FROM users WHERE user_id=?");
                $stmt->bind_param("s", $user_id);
                $stmt->execute();
                $stmt->store_result();
            } while ($stmt->num_rows > 0);

            // Insert new user
            $stmt = $conn->prepare("INSERT INTO users (user_id, username, email, password) VALUES (?, ?, ?, ?)");
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bind_param("ssss", $user_id, $username, $email, $hashed_password);

            if ($stmt->execute()) {
                echo "<script>
                        alert('Signup successful!');
                        window.location.href='login.php';
                      </script>";
                exit();
            } else {
                $errors['general'] = "Error: " . $stmt->error;
            }
        }

        $stmt->close();
        $conn->close();
    }
}

function generateUserId() {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $user_id = '';
    for ($i = 0; $i < 6; $i++) {
        $user_id .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $user_id;
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
    <title>Signup - FixMyRide</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="fixmyride">FixMyRide</div>
    <div class="container">
        <h1>Signup</h1>
        <form action="signup.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
            <span class="error"><?php echo $errors['username'] ?? ''; ?></span><br><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            <span class="error"><?php echo $errors['email'] ?? ''; ?></span><br><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <span class="error"><?php echo $errors['password'] ?? ''; ?></span><br><br>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <span class="error"><?php echo $errors['confirm_password'] ?? ''; ?></span>
            
            <a href="login.php">Already have an account?</a><br><br>

            <input type="submit" value="Signup">
            <span class="error"><?php echo $errors['general'] ?? ''; ?></span>
        </form>
    </div>
</body>
</html>
