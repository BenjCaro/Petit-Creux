<?php

use PHPUnit\Framework\TestCase;
use Carbe\Petitcreuxv2\Models\Entites\Description;
use Carbe\Petitcreuxv2\Models\Repository\DescriptionRepository;
use Carbe\Petitcreuxv2\Core\Database;

class DescriptionRepositoryTest extends TestCase {

    private DescriptionRepository $descriptionRepo;
    private PDO $pdo;
        public function setUp(): void
        {
            $this->pdo = new PDO('sqlite::memory:');
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->pdo->exec('
            CREATE TABLE descriptions (
              id INTEGER PRIMARY KEY AUTOINCREMENT,
              step_number INT,
              texte TEXT NOT NULL,
              id_recipe INT NOT NULL
            )');

            Database::createForTest($this->pdo);
            $this->descriptionRepo = new DescriptionRepository($this->pdo);

        }

        public function testcreateDescriptionRecipe() :void {
            
            $description = new Description([
                'step_number' => 1,
                'texte' => "Trempez dans l'huile",
                'id_recipe' => 1
            ]);

            $newDescription = $this->descriptionRepo->createDescriptionRecipe($description);

            $this->assertTrue($newDescription);

            $stmt = $this->pdo->query("SELECT * from descriptions WHERE step_number = 1 ");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->assertNotEmpty($row);
            $this->assertSame("Trempez dans l'huile", $row['texte']);
            
        }
    }

   // .\vendor\bin\phpunit.bat .\src\Tests\DescriptionRepositoryTest.php