<?php

use PHPUnit\Framework\TestCase;
use Carbe\App\Models\Entities\User;
Use Carbe\App\Models\Repositories\UserRepository;
use Carbe\Petitcreuxv2\Core\Database;


class UserRepositoryTest extends TestCase {

    private UserRepository $userRepository;

    protected function setUp(): void

    {
        // Création d'une base SQLite en mémoire
        $pdo = new PDO('sqlite::memory:');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Création de la table users (elle n'existe pas encore dans SQLite en mémoire)
        $pdo->exec('
            CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT,
                firstname TEXT,
                email TEXT UNIQUE,
                password TEXT,
                role TEXT
            )
        ');

        // Insertion d’un utilisateur de test
        $stmt = $pdo->prepare('
            INSERT INTO users (name, firstname, email, password, role)
            VALUES (:name, :firstname, :email, :password, :role)
        ');
        $stmt->execute([
            'name' => 'Doe',
            'firstname' => 'John',
            'email' => 'john.doe@example.com',
            'password' => password_hash('secret', PASSWORD_DEFAULT),
            'role' => 'user'
        ]);

        // Si tu veux ensuite tester ton UserRepository :
        $this->userRepository = new UserRepository($pdo);

        // Injection de cette connexion dans ta classe Database
        $testDb = new Database($pdo);
        Database::setInstance($testDb);
    }

 
    // :?User typer la methodes
    public function testFindUserWithEmail() :void  {
    
        $user = $this->userRepository->findUserWithEmail('john.doe@example.com');

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame('Doe', $user->getName());

    }
}

// .\vendor\bin\phpunit.bat .\src\Tests\UserRepositoryTest.php lancer le test