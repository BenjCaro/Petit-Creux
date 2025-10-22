<?php

use PHPUnit\Framework\TestCase;
use Carbe\Petitcreuxv2\Models\Entites\User;
use Carbe\Petitcreuxv2\Models\Repository\UserRepository;
use Carbe\Petitcreuxv2\Core\Database;


class UserRepositoryTest extends TestCase {

    private UserRepository $userRepository;
    private PDO $pdo;

    protected function setUp(): void

    {
        // Création d'une base SQLite en mémoire
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Création de la table users (elle n'existe pas encore dans SQLite en mémoire)
        $this->pdo->exec('
            CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT NULL,
                name TEXT,
                firstname TEXT,
                email TEXT UNIQUE NOT NULL,
                password TEXT,
                description TEXT, 
                role TEXT,
                createdAt TEXT
            )
        ');

        // Insertion d’un utilisateur de test
        $stmt = $this->pdo->prepare('
            INSERT INTO users (username,name, firstname, email, password,description, createdAt, role)
            VALUES (:username, :name, :firstname, :email, :password, :description, :createdAt, :role)
        ');
        $stmt->execute([
            'username' => 'JohnDoe',
            'name' => 'Doe',
            'firstname' => 'John',
            'email' => 'john.doe@example.com',
            'description' => '',
            'password' => password_hash('secret', PASSWORD_DEFAULT),
            'role' => 'user',
            'createdAt' => ''
            
        ]);

       Database::createForTest($this->pdo);

    // Création du UserRepository
    $this->userRepository = new UserRepository($this->pdo); }

 
    // :?User typer la methodes
    public function testFindUserWithValidEmail() :void  {
    
        $user = $this->userRepository->findUserWithEmail('john.doe@example.com');

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame('Doe', $user->getName());

    }

    public function testFindUserWithOtherEmail() :void {
        $user = $this->userRepository->findUserWithEmail('jane.doe@example.com');
        $this->assertNull($user);
    }

    public function testFindUserWithEmptyEmail() :void {
        $user = $this->userRepository->findUserWithEmail('');
        $this->assertNull($user);
    }


    public function testFindUserWithName() :void {

        $users = $this->userRepository->findUserWithUsername("JohnDoe");

        $this->assertIsArray($users);
        $this->assertNotEmpty($users);
        $this->assertInstanceOf(User::class, $users[0]);
        $this->assertSame('Doe', $users[0]->getName());
    }

    public function testFindUserWithWrongUsername() :void {
       
        $users = $this->userRepository->findUserWithUsername("Jane");

        $this->assertIsArray($users);
        $this->assertEmpty($users, "La recherche d'un username inexistant doit renvoyer un tableau vide");

}

    // public function testFindUserWithNullShouldThrowTypeError(): void
    //     {
    //         $this->expectException(\TypeError::class);
    //         $this->userRepository->findUserWithName(null);
    //     }

    public function testCreateNewUser() :void {
         
           $user = new User([
            'username' => 'Janyy',
            'name' => 'Doe',
            'firstname' => 'Jane',
            'email' => 'jane.doe@example.com',
            'password' => password_hash('secret', PASSWORD_DEFAULT),
            'role' => 'user',
            'description' => 'Un utilisateur de test',
            'createdAt' => ''
            
        ]);

        $result = $this->userRepository->createUser($user);

         $this->assertTrue($result);

         $stmt = $this->pdo->query("SELECT * FROM users WHERE email = 'jane.doe@example.com'");
         $row = $stmt->fetch(PDO::FETCH_ASSOC);

         $this->assertNotEmpty($row);
         $this->assertSame('Doe', $row['name']);
         $this->assertSame('Janyy', $row['username']);
         $this->assertSame('Jane', $row['firstname']);
         $this->assertSame('user', $row['role']);
    }

    /**
     * UserRepositoryTest::testCreateNewMissingEmailUser
     * Failed asserting that exception of type "PDOException" is thrown.
     * 
     * 
     * car SQLlite ne déclenche pas PDOexception
     * 
     * UserService prendra en gestion les regles de verifications liés aux emails
     */

    public function testCreateNewMissingEmailUser() :void {
         
           $user = new User([
            'username' => 'Janyy',
            'name' => 'Doe',
            'firstname' => 'Jane',
            'email' => null,
            'password' => password_hash('secret', PASSWORD_DEFAULT),
            'role' => 'user',
            'description' => 'Un utilisateur de test',
            'createdAt' => ''
            
        ]);

        $this->expectException(\PDOException::class);
        $this->userRepository->createUser($user);

    }


}

// .\vendor\bin\phpunit.bat .\src\Tests\UserRepositoryTest.php lancer le test