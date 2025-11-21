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
            'createdAt' => '18/11/2025',
            'duration' => 15,
            'state' => 'pending',
            'id_recipe' => 1,
            'id_ingredient' => [12],
            'quantity' => [250],
            'unit' => ['ml'],
            'step_number' => [1],
            'texte' => ["Trempez dans l'huile"],
            'id_recipe' => 1
            ];
     
     $newRecipe = $this->recipeService->createRecipe($data);
     $this->assertInstanceOf(Recipe::class, $newRecipe);
     $this->assertSame('Croissant au Jambon', $newRecipe->getTitle());
    }
}

// .\vendor\bin\phpunit.bat .\src\Tests\RecipeServiceTest.php