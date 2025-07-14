<?php
session_start();
include('config.php');  // Include the PDO database connection from config.php

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize the input to prevent SQL injection
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    // Prepare the query to avoid SQL injection
    $query = "SELECT * FROM users WHERE username = :username";
    $stmt = $db->prepare($query);

    // Bind the username parameter to the SQL query
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);

    // Execute the query
    $stmt->execute();

    // Fetch the user data
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify the password if user exists
    if ($user && password_verify($password, $user['password'])) {
        // If login is successful, create session
        $_SESSION['user_id'] = $user['uid'];
        header('Location: dashboard.php');
        exit();
    } else {
        // Handle failed login attempt
        $error_message = "Invalid login credentials.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 100%;
            max-width: 400px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input[type="text"], input[type="password"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            padding: 12px;
            background-color: #9a5006;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #7a4004;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
        }

        .register-link a {
            color: #9a5006;
            text-decoration: none;
            font-weight: bold;
        }

        .register-link a:hover {
            color: #7a4004;
        }

        @media (max-width: 768px) {
            .login-container {
                width: 90%;
            }
        }
    </style>
</head>
<body>

    <header style="background-color: #fff; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center;">
        <nav>
            <a href="index.php" style="margin-right: 15px; color: #9a5006; text-decoration: none;">Home</a>
            <a href="recipes.php" style="margin-right: 15px; color: #9a5006; text-decoration: none;">Recipes</a>
            <a href="register.php" style="color: #9a5006; text-decoration: none; font-weight: bold;">Register</a>
        </nav>
    </header>


    <div class="login-container">
        <h1>Login</h1>

        <!-- Display error message if there is one -->
        <?php if (isset($error_message)): ?>
            <div class="error-message">
                <p><?= htmlspecialchars($error_message) ?></p>
            </div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="username" required placeholder="Username">
            <input type="password" name="password" required placeholder="Password">
            <button type="submit">Login</button>
        </form>

        <!-- Register Now link -->
        <div class="register-link">
            <p>Don't have an account? <a href="register.php">Register Now</a></p>
        </div>
    </div>
</body>
</html>
