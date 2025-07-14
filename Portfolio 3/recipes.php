<?php
include('config.php');

// Fetch all recipes from the database, including the owner's username
$query = "SELECT recipes.*, users.username AS owner_name FROM recipes 
          LEFT JOIN users ON recipes.uid = users.uid";
$stmt = $db->prepare($query);
$stmt->execute();
$recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rumi's Kitchen</title>
    <link rel="icon" type="image/x-icon" href="images/icon.png">  
    <link rel="stylesheet" type="text/css" href="CSS/thestyle.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css"/>
    <script defer src="validation.js"></script>
</head>
<body>

<header>
    <h1>Rumi's Kitchen</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="recipes.php">Recipes</a>
        <a href="register.php">Register</a>
    </nav>
</header>

<main>
    <div class="main-header">
        <h2>Recipes for you!</h2>
    </div>

    <?php if (count($recipes) > 0): ?>
        <?php foreach ($recipes as $recipe): ?>
            <section class="recipe">
                <h4><?= htmlspecialchars($recipe['name']) ?></h4>
                <div class="container">
                    <div class="row d-flex justify-content-between g-5">
                        <div class="col-sm">
                            <div class="instructions">
                                <div class="ingredients">
                                    <p>Owner: <?= htmlspecialchars($recipe['owner_name']) ?></p>
                                    <p>Type: <?= htmlspecialchars($recipe['type']) ?></p> 
                                    <h6>Ingredients:</h6>
                                    <ul>
                                        <?php 
                                            $ingredients = explode(",", $recipe['ingredients']);
                                            foreach ($ingredients as $ingredient) {
                                                echo "<li>" . htmlspecialchars(trim($ingredient)) . "</li>";
                                            }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm">
                            <div class="recipe-image">
                                <?php if ($recipe['image']): ?>
                                    <img src="images/<?= htmlspecialchars($recipe['image']) ?>" 
                                         alt="<?= htmlspecialchars($recipe['name']) ?>" 
                                         width="400px" height="300px">
                                <?php else: ?>
                                    <p>No image available</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="cooking-step">
                    <br><br>
                    <h6>Method</h6>
                    <ol>
                        <?php 
                            $instructions = explode(",", $recipe['instructions']);
                            foreach ($instructions as $step) {
                                echo "<li>" . htmlspecialchars(trim($step)) . "</li>";
                            }
                        ?>
                    </ol>
                </div>
            </section>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No recipes available.</p>
    <?php endif; ?>
</main>

<br><br>

<footer>
    <section id="information">
        <div class="info">
            <p>Rameesa Abdul-Razaq</p>
            <p>240130808@aston.ac.uk</p>
            <p>240130808</p>
        </div>

        <div class="info">
            <p>123 Elm Street</p>
            <p>Manchester</p>
            <p>M1 2AB</p>
            <p>United Kingdom</p>
        </div>
    </section>
</footer>

</body>
</html>
