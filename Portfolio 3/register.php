<?php
session_start();
include('config.php');  // Include the PDO database connection from config.php

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize the input to prevent XSS
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    try {
        // Prepare the query to insert the new user into the database
        $query = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
        $stmt = $db->prepare($query);

        // Bind parameters to the query
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        // Execute the query
        $stmt->execute();

        // Redirect to login page after successful registration
        header('Location: login.php');
        exit();
    } catch (PDOException $e) {
        // Handle any errors (e.g., duplicate username or email)
        $error_message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        /* Styling for the registration form */
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

        .register-container {
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

        input[type="text"], input[type="email"], input[type="password"] {
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

        .login-link {
            text-align: center;
            margin-top: 20px;
        }

        .login-link a {
            color: #9a5006;
            text-decoration: none;
            font-weight: bold;
        }

        .login-link a:hover {
            color: #7a4004;
        }

        @media (max-width: 768px) {
            .register-container {
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
   
        
    <div class="register-container">
        <h1>Register</h1>

        <form method="POST">
            <input type="text" name="username" required placeholder="Username">
            <input type="email" name="email" required placeholder="Email">
            <input type="password" name="password" required placeholder="Password">
            <button type="submit">Register</button>
        </form>

        <div class="login-link">
            <p>Already have an account? <a href="login.php">Login Now</a></p>
        </div>
    </div>
   
</body>
</html>
