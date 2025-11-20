<?php

use PHPUnit\Framework\TestCase;
use Carbe\Petitcreuxv2\Models\Entites\RecipeIngredient;
use Carbe\Petitcreuxv2\Models\Repository\RecipeIngredientRepository;
use Carbe\Petitcreuxv2\Core\Database;

class RecipeIngredientRepositoryTest extends TestCase {

    private PDO $pdo;
    private RecipeIngredientRepository $recipeIngredienRepo;

    public function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->pdo->exec('CREATE TABLE recipes_ingredients (
            id_recipe INT NOT NULL,
            id_ingredient INT NOT NULL,
            quantity INT NOT NULL,
            unit TEXT NOT NULL
         )');

         Database::createForTest($this->pdo);
         $this->recipeIngredienRepo = new RecipeIngredientRepository($this->pdo);
    }

    public function testCreateRecipeIngredient() :void {
        $data = new RecipeIngredient([
            'id_recipe' => 1,
            'id_ingredient' => 12,
            'quantity' => 250,
            'unit' => 'ml'
        ]);

        $newIngredient = $this->recipeIngredienRepo->createRecipeIngredient($data);

        $this->assertTrue($newIngredient);

        $stmt = $this->pdo->query("SELECT * FROM recipes_ingredients WHERE id_recipe = 1");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertNotEmpty($row);
        $this->assertSame(12, $row['id_ingredient']);
        $this->assertSame(250, $row['quantity']);
        $this->assertSame('ml', $row['unit']);
    }
}

// .\vendor\bin\phpunit.bat --display-warnings .\src\Tests\RecipeIngredientRepositoryTest.php