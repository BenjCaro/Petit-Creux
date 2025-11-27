<?php 

use PHPUnit\Framework\TestCase;
use Carbe\Petitcreuxv2\Services\RecipeService;
use Carbe\Petitcreuxv2\Core\Database;
use Carbe\Petitcreuxv2\Exceptions\ValidationException;
use Carbe\Petitcreuxv2\Models\Entites\Recipe;
use Carbe\Petitcreuxv2\Models\Repository\RecipeRepository;
use Carbe\Petitcreuxv2\Models\Entites\Description;
use Carbe\Petitcreuxv2\Models\Repository\DescriptionRepository;
use Carbe\Petitcreuxv2\Models\Entites\RecipeIngredient;
use Carbe\Petitcreuxv2\Models\Repository\RecipeIngredientRepository;

class RecipeServiceTest extends TestCase {
   
    private PDO $pdo;
    private RecipeRepository $recipeRepository;
    private RecipeIngredientRepository $recipeIngredientRepo;
    private DescriptionRepository $descriptionRepo;
    private RecipeService $recipeService;


    public function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->pdo->exec('
            CREATE TABLE recipes (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT UNIQUE NOT NULL,
                slug TEXT UNIQUE NOT NULL,
                id_user INT NOT NULL,
                id_category INT NOT NULL,
                createdAt TEXT,
                duration INT NOT NULL,
                state TEXT NOT NULL DEFAULT \'pending\',
                CHECK (state IN (\'pending\', \'published\'))
            )
        ');

        $this->pdo->exec(
                'CREATE TABLE recipes_ingredients (
                    id_recipe INT NOT NULL,
                    id_ingredient INT NOT NULL,
                    quantity INT NOT NULL,
                    unit TEXT NOT NULL
                )');

        $this->pdo->exec('
            CREATE TABLE descriptions (
              id INTEGER PRIMARY KEY AUTOINCREMENT,
              step_number INT,
              texte TEXT NOT NULL,
              id_recipe INT NOT NULL
            )');

         $this->pdo->exec("
            INSERT INTO recipes (title, slug, id_user, id_category, createdAt, duration, state)
            VALUES ('Recette Test', 'recette-test', 1, 1, datetime('now'), 30, 'pending')
        ");    

        Database::createForTest($this->pdo);
        $this->recipeRepository = new RecipeRepository($this->pdo);
        $this->recipeIngredientRepo = new RecipeIngredientRepository($this->pdo);
        $this->descriptionRepo = new DescriptionRepository($this->pdo);
        $this->recipeService = new RecipeService($this->recipeRepository, $this->recipeIngredientRepo, $this->descriptionRepo );
    
    }

    public function testCreateRecipe() :void {
       
        $data = [
            'title' => 'Croissant au Jambon',
            'slug' => 'croissant-au-jambon',
            'id_user' => 1,
            'id_category' => 1,
            'duration' => 15,

           
            'ingredients' => [12],
            'quantites'   => [250],
            'unit'        => ['ml'],

            'step_number' => [1],
            'texte'       => ["Trempez dans l'huile"],
        ];

     
     $newRecipe = $this->recipeService->createRecipe($data);
     $this->assertInstanceOf(Recipe::class, $newRecipe);
     $this->assertSame('Croissant au Jambon', $newRecipe->getTitle());
    }

    public function testTitleErrorCreateRecipe() :void {
        $data = [
            'title' => null,
            'slug' => 'croissant-au-jambon',
            'id_user' => 1,
            'id_category' => 1,
            'duration' => 15,

           
            'ingredients' => [12],
            'quantites'   => [250],
            'unit'        => ['ml'],

            'step_number' => [1],
            'texte'       => ["Trempez dans l'huile"],
        ];

        try {

            $newRecipe = $this->recipeService->createRecipe($data);
            $this->fail("Une validationException aurait dû être levée!");


        } catch(ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertArrayHasKey('title', $errors);
            $this->assertEquals("Veuillez donner un titre à la recette.", $errors['title']);
        }

    }

    public function testCreateRecipeWithExistingTitle() :void {
        $data = [
            'title' => 'Recette Test',
            'slug' => 'recette-test',
            'id_user' => 1,
            'id_category'=> 1,
            'createdAt' =>'',
            'duration' => 30,
            'state' => 'pending',

            'ingredients' => [12],
            'quantites'   => [250],
            'unit'        => ['grammes'],

            'step_number' => [1],
            'texte'       => ["Etape test"],

        ];

        

        try {
            $recipe = $this->recipeService->createRecipe($data);
            $this->fail("Une validationException aurait dû être levée!");
        } catch(ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertArrayHasKey('title', $errors);
            $this->assertEquals("Le titre de la recette existe déja sur Petit-Creux", $errors['title']);
        }
    }
}

// .\vendor\bin\phpunit.bat --display-deprecations .\src\Tests\RecipeServiceTest.php
// .\vendor\bin\phpunit.bat --display-warnings .\src\Tests\RecipeServiceTest.php