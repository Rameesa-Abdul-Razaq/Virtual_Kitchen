<?php
include('config.php');

// Fetch the recipe to be updated by its ID
if (isset($_GET['id'])) {
    $recipe_id = $_GET['id'];

   // $query = "SELECT recipes.*, users.username AS owner_name FROM recipes 
             // LEFT JOIN users ON recipes.uid = users.id WHERE recipes.id = :id";
	$query = "SELECT recipes.*, users.username AS owner_name FROM recipes 
          LEFT JOIN users ON recipes.uid = users.uid WHERE recipes.rid = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $recipe_id, PDO::PARAM_INT);
    $stmt->execute();
    $recipe = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$recipe) {
        die("Recipe not found.");
    }
} else {
    die("No recipe ID provided.");
}

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $ingredients = $_POST['ingredients'];
    $instructions = $_POST['instructions'];
    $image = $_FILES['image']['name'];

    // Optional: handle image upload (you can skip this if you don't want to update the image)
    if ($image) {
        move_uploaded_file($_FILES['image']['tmp_name'], 'images/' . $image);
    } else {
        $image = $recipe['image']; // Keep the existing image if no new image is uploaded
    }

    // Update the recipe in the database
   $update_query = "UPDATE recipes SET 
                 name = :name, 
                 ingredients = :ingredients, 
                 instructions = :instructions, 
                 image = :image 
                 WHERE rid = :id";
    $update_stmt = $db->prepare($update_query);
    $update_stmt->bindParam(':name', $name);
    $update_stmt->bindParam(':ingredients', $ingredients);
    $update_stmt->bindParam(':instructions', $instructions);
    $update_stmt->bindParam(':image', $image);
    $update_stmt->bindParam(':id', $recipe_id, PDO::PARAM_INT);
    
    if ($update_stmt->execute()) {
        echo "Recipe updated successfully!";
        header('Location: recipes.php'); // Redirect to recipes page after update
        exit();
    } else {
        echo "Failed to update recipe.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Recipe</title>
    <link rel="stylesheet" href="CSS/thestyle.css">
</head>
<body>

<header>
    <h1>Update Recipe</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="recipes.php">Recipes</a>
    </nav>
</header>

<main>
    <h2>Edit Recipe: <?= htmlspecialchars($recipe['name']) ?></h2>
    
    <form method="POST" enctype="multipart/form-data">
        <div>
            <label for="name">Recipe Name:</label>
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($recipe['name']) ?>" required>
        </div>
        
        <div>
            <label for="ingredients">Ingredients:</label>
            <textarea name="ingredients" id="ingredients" required><?= htmlspecialchars($recipe['ingredients']) ?></textarea>
        </div>
        
        <div>
            <label for="instructions">Instructions:</label>
            <textarea name="instructions" id="instructions" required><?= htmlspecialchars($recipe['instructions']) ?></textarea>
        </div>

        <div>
            <label for="image">Recipe Image:</label>
            <input type="file" name="image" id="image">
            <p>Current image: <?= htmlspecialchars($recipe['image']) ?></p>
        </div>
        
        <button type="submit">Update Recipe</button>
    </form>
</main>

</body>
</html>
