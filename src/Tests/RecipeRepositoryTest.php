<?php 

use PHPUnit\Framework\TestCase;
use Carbe\Petitcreuxv2\Models\Entites\Recipe;
use Carbe\Petitcreuxv2\Models\Repository\RecipeRepository;
use Carbe\Petitcreuxv2\Core\Database;

class RecipeRepositoryTest extends TestCase {

    private RecipeRepository $recipeRepository;
    private PDO $pdo;

    protected function setUp(): void
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

    Database::createForTest($this->pdo);
    $this->recipeRepository = new RecipeRepository($this->pdo);
    }

 public function testCreateRecipe() :void {
     $recipe = new Recipe([
       'title' => 'Croissant au Jambon',
       'slug' => 'croissant-au-jambon',
       'id_user' => 1,
       'id_category' => 1,
       'createdAt' => '18/11/2025',
       'duration' => 15,
       'state' => 'pending'
     ]);

     $newRecipe = $this->recipeRepository->createRecipe($recipe);

     $this->assertTrue($newRecipe);

     $stmt = $this->pdo->query("SELECT * FROM recipes WHERE title = 'Croissant au Jambon'");
     $row = $stmt->fetch(PDO::FETCH_ASSOC);

     $this->assertNotEmpty($row);
     $this->assertSame('Croissant au Jambon', $row['title']);
     $this->assertSame('croissant-au-jambon', $row['slug']);
     $this->assertSame(15, $row['duration']);
 }
}

// .\vendor\bin\phpunit.bat .\src\Tests\RecipeRepositoryTest.php lancer le test