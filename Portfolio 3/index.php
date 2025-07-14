<?php
// Include the database connection
include('config.php');

// Initialize the search term
$search_term = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and capture the search term
    $search_term = htmlspecialchars($_POST['search']);
}

// Prepare the search term for the query
$like_term = "%" . $search_term . "%";  // Make it wildcard for SQL LIKE

$results = [];

$query = "SELECT rid, name, type, description FROM recipes WHERE name LIKE :name_term OR type LIKE :type_term";

try {
    $stmt = $db->prepare($query);
    $stmt->bindParam(':name_term', $like_term, PDO::PARAM_STR);
    $stmt->bindParam(':type_term', $like_term, PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll();  
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}




// Fetch all recipes from the database
$query = "SELECT rid, name, type, description, image FROM recipes";
$recipes = [];
try {
    $stmt = $db->prepare($query);
    $stmt->execute();
    $recipes = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Rumi's Kitchen</title>
        <link rel="icon" type="image/x-icon" href="images\icon.png">     
        <link rel="stylesheet" type="text/css" href="CSS/thestyle.css"/>
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css"/>
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
        <div class="background-image">
        <img src="images\background.jpg" alt="pasta" />
                <br><br> <br><br> 

                <section id="about-us">     
                    <h2>About Us</h2>
                          <p> Welcome to Rumy's Kitchen, your go-to destination for delicious,easy-to-follow recipes!
                                <br><br>
                                Where tradition meets innovation in every bite of food.
                                <br><br>
                                From comforting classics to adventurous new flavors,our step-by-step guides,helpful tips.
                                <br><br>
                                Join our community and start creating memorable meals today!
                            </p>
                </section>
        </div>
                <br><br>
                <section id="search">
                <h2>Search Recipes</h2>
                    <form method="POST" >
                        <input type="text" name="search" value="<?= htmlspecialchars($search_term) ?>" placeholder="Search Recipes by Name or Type">
                        <button type="submit">Search</button>
                    </form>
                </section>

                <!-- the results from the search form -->
                 <br><br>

                <?php if (!empty($search_term)): ?>
                    <section id="search-results">
                        <h2>Search Results</h2>
                        <?php if (!empty($results) > 0): ?>  <!-- Checking if there are any results -->
                            <div class="container">
                                <?php foreach ($results as $row): ?> <!-- Using foreach since $results is an array -->
                                    <div class="col-md-4 mb-4">
   										 <div class="card h-100 shadow-sm">
     										   <div class="card-body">
         										   <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
          										  <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($row['type']) ?></h6>
          										 <p><?= htmlspecialchars($row['description']) ?></p>
           										 <a href="recipes.php?rid=<?= $row['rid'] ?>" class="btn btn-primary">View Recipe</a>
       										 </div>
   										 </div>
									</div>

                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p>No recipes found matching your search.</p>
                        <?php endif; ?>
                    </section>
                <?php endif; ?>         
            
            </div>


  <!-- Recipes Section -->
<section id="recipes">
    <h2>Recipes</h2>
    <br><br>

    <div class="blockquote">
        <blockquote>
            <h5>These are simple, delicious recipes. Never mistake simple for bland!</h5>
        </blockquote>
    </div>

    <br><br>

    <div class="container">
        <div class="row d-flex justify-content-between g-5">  <!-- Responsive layout using bootstrap -->
            <?php foreach ($recipes as $row): ?>  <!-- Loop through each recipe from the database -->
                <div class="col-sm">
                    <a href="recipe.php?rid=<?= $row['rid'] ?>" style="text-decoration: none;">
                        <div class="card h-100 shadow-sm">
                            <img src="images/<?= htmlspecialchars($row['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['name']) ?>" width="300" height="300">
                            <div class="card-body">
                                <h3 class="card-title"><?= htmlspecialchars($row['name']) ?></h3>
                                <h5 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($row['type']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($row['description']) ?></p>
                                <a href="recipes.php?rid=<?= $row['rid'] ?>" class="btn btn-primary">View Recipe</a>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
        </main>
        <br><br>
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