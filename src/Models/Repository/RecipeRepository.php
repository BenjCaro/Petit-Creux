<?php 

namespace Carbe\App\Models;


use Carbe\App\Models\Entities\Ingredient;
use Carbe\App\Models\Entities\RecipeIngredient;
use Carbe\App\Models\Repositories\BaseRepository;
use Carbe\App\Models\Entities\Recipe;
use Carbe\App\Models\Entities\User;
use Carbe\App\Models\Entities\Category;
use PDO;



class RecipeRepository extends BaseRepository {


 protected string $table = 'recipes';
 public function __construct()
    {
        parent::__construct();
    }


/**
 * @return null Si aucune recette n'est trouvée pour le slug donné
 * @return RecipeModel
 */

public function getRecipeBySlug(string $slug) :?Recipe {  
    $stmt = $this->pdo->prepare("SELECT
     recipes.id AS recipe_id, 
     recipes.title, 
     recipes.slug,
     recipes.duration, 
     recipes.description,
     recipes.state,
     recipes.createdAt,
     ingredients.name,
     ingredients.id,
     recipes_ingredients.quantity,
     recipes_ingredients.unit,
     categories.name AS category_name,
     users.name AS user_name,
     users.firstname AS user_firstname
    FROM recipes 
    LEFT JOIN recipes_ingredients ON recipes.id = recipes_ingredients.id_recipe
    LEFT JOIN ingredients ON ingredients.id = recipes_ingredients.id_ingredient
    JOIN categories ON categories.id = recipes.id_category
    JOIN users ON users.id = recipes.id_user
    WHERE recipes.slug = :slug");
    $stmt->execute([
       'slug' => $slug
    ]);
   $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
   if (!$data) {
       return null;
    }

    
    $recipe = new Recipe([
        'id' => $data[0]['recipe_id'],
        'slug' => $data[0]['slug'],
        'title' => $data[0]['title'],
        'duration' => $data[0]['duration'],
        'description' => $data[0]['description'],
        'state' => $data[0]['state'],
        'createdAt' => $data[0]['createdAt']

    ]);
    
    $ingredients = [];
    foreach ($data as $row) {

        if($row['id'] !== null) {
        $ingredient = new Ingredient([ 'id' => $row['id'],
            'name' => $row['name']]);


        $recipeIngredient = new RecipeIngredient([
            'quantity' => $row['quantity'],
            'unit' => $row['unit']
        ]);
        $recipeIngredient->setIngredient($ingredient);

        $ingredients[] = $recipeIngredient;
    }
}

    $user = new User(['name' => $row['user_name'],
                    'firstname' => $row['user_firstname']
    ]);
    
    $recipe->setUser($user);
    $recipe->setIngredients($ingredients);
    return $recipe;
}

/**
 * @return RecipeModel|null
 */

public function newRecipe() :?Recipe {
      $stmt = $this->pdo->prepare("
      SELECT 
         recipes.id, 
         recipes.title, 
         recipes.slug AS recipe_slug, 
         recipes.createdAt, recipes.duration, 
         recipes.description, 
         categories.id AS category_id, 
         categories.name AS category_name, 
         categories.slug AS category_slug 
      FROM recipes 
      JOIN categories ON id_category = categories.id 
      WHERE recipes.state = 'published'
      ORDER BY createdAt
      DESC LIMIT 1;");

      $stmt->execute();
      $data = $stmt->fetch(PDO::FETCH_ASSOC);
      
      if (!$data) {
        return null;
    }
      
         $categoryData = [
        'id' => $data['category_id'],
        'name' => $data['category_name'],
        'slug' => $data['category_slug']
    ];

        $category = new Category($categoryData);
        
        $recipe = new Recipe([
            'id' => $data['id'],
            'title' => $data['title'],
            'slug' => $data['recipe_slug'],
            'createdAt' => $data['createdAt'],
            'duration' => $data['duration'],
            'description' => $data['description']
        ]);
        
        $recipe->setCategory($category);

        return $recipe;

   }

public function getMostPopularRecipe() :?Recipe {
    $stmt = $this->pdo->prepare("
        SELECT 
            recipes.id AS recipe_id,
            recipes.title AS recipe_title,
            recipes.slug AS recipe_slug,
            recipes.id_user,
            recipes.id_category,
            recipes.createdAt,
            recipes.duration,
            recipes.description,
            categories.id AS category_id,
            categories.name AS category_name,
            categories.slug AS category_slug,
            COUNT(favoris.id_recipe) AS popularity
        FROM recipes
        JOIN favoris ON recipes.id = favoris.id_recipe
        JOIN categories ON recipes.id_category = categories.id
        WHERE recipes.state = 'published'
        GROUP BY recipes.id
        ORDER BY popularity DESC
        LIMIT 1
    ");
    
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {
       
        $category = new Category([
            'id' => $data['category_id'],
            'name' => $data['category_name'],
            'slug' => $data['category_slug']
        ]);

        $recipe = new Recipe([
            'id' => $data['recipe_id'],
            'title' => $data['recipe_title'],
            'slug' => $data['recipe_slug'],
            'idUser' => $data['id_user'],
            'idCategory' => $data['id_category'],
            'createdAt' => $data['createdAt'],
            'duration' => $data['duration'],
            'description' => $data['description']
        ]);

        $recipe->setCategory($category);

        return $recipe;
    }

    return null;
}

 /**
 * @return RecipeModel[]
 */

public function getAllRecipesWithCategory() :array {
      $stmt = $this->pdo->prepare("SELECT * FROM `recipes`
                              JOIN categories ON recipes.id_category = categories.id");
     $stmt->execute();

      $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $recipes = [];

      foreach($results as $data) {
            $categoryData = [
         'id' => $data['id'],
         'name' => $data['name']
      ];

         $category = new Category($categoryData);

         $recipe = new Recipe($data);
         $recipe->setCategory($category);

         $recipes[] = $recipe;

      }

      return $recipes;
    
}

 /**
 * @return RecipeModel[]
 */
public function getAllPublishRecipesByCategory(int $idCategory) :array {
    $stmt = $this->pdo->prepare("
    SELECT 
    recipes.id AS recipe_id, 
    recipes.title, 
    recipes.id_user, 
    recipes.slug,
    recipes.createdAt, 
    recipes.duration, 
    recipes.description, 
    categories.name, 
    categories.id AS category_id,
    categories.image 
    FROM `recipes` 
    JOIN categories ON recipes.id_category = categories.id 
    WHERE categories.id = :id AND recipes.state = 'published'"
    );

    $stmt->execute(['id' => $idCategory]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $recipes = [];

    foreach($results as $data) {

      $categoryData = ['id' => $data['category_id'],
                       'name' => $data['name'],
                       'image'=> $data['image']
                     ];

      $category = new Category($categoryData);

      $recipe = new Recipe($data);
      $recipe->setCategory($category);

      $recipes[] = $recipe;

    }

    return $recipes;
}

 /**
 * @return RecipeModel[]
 */
public function getAllRecipesByCategory(int $idCategory) :array {
    $stmt = $this->pdo->prepare("
    SELECT 
    recipes.id AS recipe_id, 
    recipes.title, 
    recipes.id_user, 
    recipes.slug,
    recipes.createdAt, 
    recipes.duration, 
    recipes.description, 
    recipes.state,
    categories.name, 
    categories.id AS category_id,
    categories.image 
    FROM `recipes` 
    JOIN categories ON recipes.id_category = categories.id 
    WHERE categories.id = :id AND recipes.state = 'published' "
    );

    $stmt->execute(['id' => $idCategory]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $recipes = [];

    foreach($results as $data) {

      $categoryData = ['id' => $data['category_id'],
                       'name' => $data['name'],
                       'image'=> $data['image']
                     ];

      $category = new Category($categoryData);

      $recipe = new Recipe($data);
      $recipe->setCategory($category);

      $recipes[] = $recipe;

    }

    return $recipes;
}

/**
 * @return RecipeModel[]
 */

public function getRecipesByUser(int $idUser) :array {
    $stmt = $this->pdo->prepare('SELECT recipes.id, recipes.title, recipes.slug, categories.name 
        FROM `recipes` 
        JOIN categories ON categories.id = recipes.id_category
        WHERE recipes.id_user = :id_user');

    $stmt->execute([
        'id_user' => $idUser
    ]);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $recipes = [];

    foreach($results as $data) {
        $categoryName = ['name' => $data['name']];
        $category = new Category($categoryName);
        $recipe = new Recipe($data);
        $recipe->setCategory($category);

        $recipes[] = $recipe;
    }

    return $recipes;
    
    }

/**
 * @return RecipeModel[]
 */

public function getLastestRecipes() :array {
    $stmt = $this->pdo->prepare('
    SELECT 
    recipes.id AS recipe_id, 
    recipes.title, 
    recipes.id_user, 
    recipes.slug,
    recipes.createdAt, 
    recipes.state,
    categories.name, 
    categories.id AS category_id,
    users.id AS user_id,
    users.name AS user_name,
    users.firstname AS user_firstname
    FROM `recipes` 
    JOIN categories ON recipes.id_category = categories.id 
    JOIN users ON recipes.id_user = users.id
    ORDER BY recipes.createdAt DESC
    LIMIT 5;
    ');

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $recipes = [];

    foreach($results as $data) {

      $categoryData = ['id' => $data['category_id'],
                       'name' => $data['name']
                     ];

      $category = new Category($categoryData);

      $userData = ['id' => $data['user_id'],
                   'name' => $data['user_name'],
                   'firstname' => $data['user_firstname']
                  ];
     $user = new User($userData);

      $recipe = new Recipe($data);
      $recipe->setCategory($category);
      $recipe->setUser($user);

      $recipes[] = $recipe;

    }

    return $recipes;
}

/**
 * 
 * @return RecipeModel[]|null
 */
 
public function getAllRecipes() :array {
    $stmt = $this->pdo->prepare("SELECT 
    recipes.id AS recipe_id, 
    recipes.title, 
    recipes.id_user, 
    recipes.slug,
    recipes.createdAt,
    recipes.state,
    categories.name, 
    categories.id AS category_id,
    users.id AS user_id,
    users.name AS user_name,
    users.firstname AS user_firstname
    FROM `recipes` 
    JOIN categories ON recipes.id_category = categories.id 
    JOIN users ON recipes.id_user = users.id");

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $recipes = [];

    foreach($results as $data) {

      $categoryData = ['id' => $data['category_id'],
                       'name' => $data['name']
                     ];

      $category = new Category($categoryData);

      $userData = ['id' => $data['user_id'],
                   'name' => $data['user_name'],
                   'firstname' => $data['user_firstname']
                  ];
     $user = new User($userData);

      $recipe = new Recipe($data);
      $recipe->setCategory($category);
      $recipe->setUser($user);

      $recipes[] = $recipe;

    }

    return $recipes;

}

/**
 * 
 * @return RecipeModel[]|null
 */

public function searchRecipeWithTitle(string $search) :?array {  
    $stmt = $this->pdo->prepare("SELECT
     recipes.id AS recipe_id, 
     recipes.title, 
     recipes.slug,
     recipes.duration, 
     recipes.description,
     recipes.state,
     recipes.createdAt,
     categories.name AS category_name,
     users.name AS user_name,
     users.firstname AS user_firstname
    FROM recipes 
    JOIN categories ON categories.id = recipes.id_category
    JOIN users ON users.id = recipes.id_user
    WHERE LOWER(title) LIKE LOWER(:search)");

    $stmt->execute([
       'search' => "%$search%"
    ]);

   $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
   if (!$data) {
       return null;
    }

    $recipes = [];

    foreach($data as $row) {
        $recipe = new Recipe($row);

        $category = new Category([
            "name" => $row['category_name']
        ]);

        $recipe->setCategory($category);

        $user = new User([
            "name" => $row['user_name'],
            "firstname" => $row['user_firstname']
        ]);

        $recipe->setUser($user);
        $recipes[] = $recipe;
    }

        return $recipes;

    }

    /**
* Recherche des recettes par titre.
*
* @param string $search
* @return array{recipes: RecipeModel[], totalResults: int}
*/

    public function getRecipeWithTitle(string $search) :array {

        $stmt = $this->pdo->prepare("SELECT 
            recipes.title, 
            recipes.slug, 
            recipes.duration, 
            categories.name AS category_name,
            COUNT(*) OVER() AS total_results
            FROM recipes
            JOIN categories ON categories.id = recipes.id_category
            WHERE recipes.title LIKE :search AND recipes.state = 'published';
        ");
        $stmt->execute(['search' => "%$search%"]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $totalResults = $results ? (int)$results[0]['total_results'] : 0;

        $recipes = [];
        foreach($results as $data) {
            $recipe = new Recipe($data);
            $category = new Category(['name' =>$data['category_name']]);
            $recipe->setCategory($category);
            $recipes[] = $recipe;
        }

        return [
        'recipes' => $recipes,
        'totalResults' => $totalResults
    ];
}
 }

