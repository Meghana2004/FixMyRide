<?php
$errors = [];
$username = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty($_POST['username'])) {
        $errors['username'] = "Username is required";
    } else {
        $username = test_input($_POST['username']);
    }

    // Validate password
    if (empty($_POST['password'])) {
        $errors['password'] = "Password is required";
    } else {
        $password = test_input($_POST['password']);
    }

    // Check if the credentials are correct
    if (empty($errors)) {
        if ($username === 'admin' && $password === 'admin') {
            // Redirect user to admin home page if credentials are correct
            header("Location: admin_home.php");
            exit();
        } else {
            $errors['login'] = "Invalid username or password";
        }
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
    <title>Login - Admin Panel</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .fixmyride {
            position: absolute;
            left: 20px;
            top: 20px;
            font-size: 24px;
            font-weight: bold;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: #fff;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        h1 {
            color: #e8491d;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            text-align: left;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: calc(100% - 20px);
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background: #e8491d;
            color: #fff;
            border: 0;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 4px;
            width: 100%;
            font-size: 16px;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
            display: block;
        }
    </style>
</head>
<body>
    <div class="fixmyride">FixMyRide</div>
    <div class="container">
        <h1>Admin Login</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>
            <span class="error"><?php echo $errors['username'] ?? ''; ?></span><br><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <span class="error"><?php echo $errors['password'] ?? ''; ?></span><br>

            <input type="submit" value="Login">
            <span class="error"><?php echo $errors['login'] ?? ''; ?></span>
        </form>
    </div>
</body>
</html>
