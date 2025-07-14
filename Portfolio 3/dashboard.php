<?php
session_start();
include('config.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to login page
    header('Location: login.php');
    exit();
}

//the logout
if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    // sends to login page
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit();
}

// Get the user's ID from the session
$user_id = $_SESSION['user_id'];

// Query to get the user's username from the database
$query = "SELECT username FROM users WHERE uid = :user_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

//get the recipes
$recipe_query = "SELECT * FROM recipes WHERE uid = :user_id";
$recipe_stmt = $db->prepare($recipe_query);
$recipe_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$recipe_stmt->execute();
$recipes = $recipe_stmt->fetchAll(PDO::FETCH_ASSOC);


//adding the new recipe
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_recipe'])) {
    $recipe_name = htmlspecialchars($_POST['recipe_name']);
    $recipe_type = htmlspecialchars($_POST['recipe_type']);
    $recipe_ingredients = htmlspecialchars($_POST['recipe_ingredients']);
    $recipe_instructions = htmlspecialchars($_POST['recipe_instructions']);
	$recipe_description = htmlspecialchars($_POST['description']);

    // the images uploaded for new recipes
    if (isset($_FILES['recipe_image']) && $_FILES['recipe_image']['error'] == 0) {
        // Store image in the "images" folder
        $image_name = basename($_FILES['recipe_image']['name']);
        $image_path = 'images/' . $image_name;
        $image_extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
    
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($image_extension, $allowed_extensions)) {
            if (move_uploaded_file($_FILES['recipe_image']['tmp_name'], $image_path)) {
                echo "Image uploaded successfully!";
            } else {
                echo "Failed to move the uploaded file.";
            }
        } else {
            echo "Invalid image file type. Please upload a jpg, jpeg, png, or gif.";
        }
    }
    

    // Insert into Database
    $insert_query = "INSERT INTO recipes (name, type, ingredients, instructions, image, uid, description) 
    VALUES (:recipe_name, :recipe_type, :recipe_ingredients, :recipe_instructions, :recipe_image, :user_id, :description)";
    $stmt = $db->prepare($insert_query);
    $stmt->bindParam(':recipe_name', $recipe_name);
    $stmt->bindParam(':recipe_type', $recipe_type);
    $stmt->bindParam(':description', $recipe_description);
    $stmt->bindParam(':recipe_ingredients', $recipe_ingredients);
    $stmt->bindParam(':recipe_instructions', $recipe_instructions);
    $stmt->bindParam(':recipe_image', $image_path); 
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    //  dashboard after adding a recipe
if ($stmt->execute()) {
    header('Location: dashboard.php');
    exit();
} else {
    $errorInfo = $stmt->errorInfo();
    echo "Insert error: " . $errorInfo[2];
    exit();
}


    // header('Location: dashboard.php');
    // exit();
}


// If user data is found, we can display the username
if ($user) {
    $username = $user['username'];
} else {
    // In case the user is not found in the database
    echo "User not found!";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        h1, h2, h3 {
            text-align: center;
            color: #4b4b4b;
        }

        /* Header Styling */
        h1 {
            font-size: 2em;
            margin: 20px 0;
            color: #2c3e50;
        }

        h2 {
            font-size: 1.8em;
            margin: 20px 0;
            color: #2980b9;
        }

        h3 {
            font-size: 1.5em;
            margin-top: 40px;
            color: #2c3e50;
        }

        /* Recipe List Styling */
        ul {
            list-style-type: none;
            padding: 0;
            margin: 20px;
        }

        li {
            background-color: #ecf0f1;
            border-radius: 5px;
            margin: 10px 0;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        li a {
            color: #2980b9;
            text-decoration: none;
            margin-right: 10px;
        }

        li a:hover {
            text-decoration: underline;
        }

        /* Recipe Form Styling */
        form {
            width: 60%;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        form label {
            font-size: 1.1em;
            margin-bottom: 5px;
            display: block;
        }

        form input, form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
        }

        form textarea {
            height: 150px;
        }

        form button {
            padding: 10px 20px;
            background-color:rgb(51, 51, 51);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.2em;
            cursor: pointer;
        } 

        /* Logout button */
        p a {
            display: block;
            width: 200px;
            text-align: center;
            background-color:rgb(54, 54, 53);
            color: white;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px auto;
        }


        @media screen and (max-width: 768px) {
            form {
                width: 90%;
            }

            h1, h2, h3 {
                font-size: 1.5em;
            }

            li {
                flex-direction: column;
                align-items: flex-start;
            }

            li a {
                margin-right: 0;
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <h1>Welcome to your Dashboard, <?= htmlspecialchars($username) ?></h1>

    <!-- Display all the user's recipes -->
    <h2>Your Recipes</h2>
    
    <?php if (count($recipes) > 0): ?>
        <?php foreach ($recipes as $recipe): ?>
            <section class="recipe">
                <h4><?= htmlspecialchars($recipe['name']) ?></h4>
                
                <!-- Recipe details -->
                <div class="recipe-details">
                    <p>Owner: <?= htmlspecialchars($recipe['owner_name']) ?></p>
                    <p>Type: <?= htmlspecialchars($recipe['type']) ?></p>
                    <p>Ingredients: <?= htmlspecialchars($recipe['ingredients']) ?></p>
                    <p>Instructions: <?= htmlspecialchars($recipe['instructions']) ?></p>

                    <!-- Update Button -->
                    <a href="update.php?id=<?= $recipe['id'] ?>" class="update-button">Update</a>
                </div>
                
                <?php if ($recipe['image']): ?>
                    <img src="images/<?= htmlspecialchars($recipe['image']) ?>" alt="<?= htmlspecialchars($recipe['name']) ?>" width="200px" height="150px">
                <?php else: ?>
                    <p>No image available</p>
                <?php endif; ?>
            </section>
        <?php endforeach; ?>
    <?php else: ?>
        <p>You have not posted any recipes yet.</p>
    <?php endif; ?>
</main>

    <!-- Form to add a new recipe -->
    <h3>Add a New Recipe</h3>
        <form method="POST" enctype="multipart/form-data">
            <label for="recipe_name">Recipe Name:</label>
            <input type="text" name="recipe_name" id="recipe_name" required><br>
            
            <label for="recipe_type">Recipe Type:</label>
            <input type="text" name="recipe_type" id="recipe_type" required><br>

            <label for="description">Description:</label>
            <input type="text" name="description" id="description" required><br>
            
            <label for="recipe_ingredients">Ingredients:</label>
            <textarea name="recipe_ingredients" id="recipe_ingredients" required></textarea><br>
            
            <label for="recipe_instructions">Instructions:</label>
            <textarea name="recipe_instructions" id="recipe_instructions" required></textarea><br>

            <label for="recipe_image">Recipe Image:</label>
            <input type="file" name="recipe_image" id="recipe_image"><br>
            
            <button type="submit" name="add_recipe">Add Recipe</button>
     </form>


    <p><a href="dashboard.php?logout=true">Logout</a></p>
</body>
</html>
