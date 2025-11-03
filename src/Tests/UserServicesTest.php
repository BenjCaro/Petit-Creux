<?php

use PHPUnit\Framework\TestCase;
use Carbe\Petitcreuxv2\Core\Database;
use Carbe\Petitcreuxv2\Services\UserServices;
use Carbe\Petitcreuxv2\Models\Entites\User;
use Carbe\Petitcreuxv2\Models\Repository\UserRepository;
use Carbe\Petitcreuxv2\Exceptions\ValidationException;

class UserServicesTest extends TestCase {

    private PDO $pdo;
    private UserServices $userService;
    private UserRepository $userRepo;


    protected function setUp() :void {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->pdo->exec('
            CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT UNIQUE NOT NULL,
                name TEXT,
                firstname TEXT,
                email TEXT UNIQUE NOT NULL,
                password TEXT,
                description TEXT, 
                role TEXT DEFAULT "user",
                createdAt TEXT
            )
        ');

        $stmt = $this->pdo->prepare('
            INSERT INTO users (username,name, firstname, email, password,description, createdAt, role)
            VALUES (:username, :name, :firstname, :email, :password, :description, :createdAt, :role)
        ');
        $stmt->execute([
            'username' => 'Jane12',
            'name' => 'Doe',
            'firstname' => 'Jane',
            'email' => 'jane.doe@example.com',
            'description' => 'Bonjour, je réalise un test!',
            'password' => password_hash('secret', PASSWORD_DEFAULT),
            'role' => 'user',
            'createdAt' => ''
            
        ]);

        Database::createForTest($this->pdo);
        $this->userRepo = new UserRepository($this->pdo);
        $this->userService = new UserServices($this->userRepo);

    }


    public function testRegisterUser() :void {

        $data = [
            'username' => "Johnny",
            'email' => "johndoe@mail.com",
            'name' => 'Doe',
            'firstname' => 'John',
            'password' => '12345678',
            'confirm-password' => '12345678',
            'description' => 'Bonjour, je réalise un test!',
            'createdAt' => '27.10.2025'
        ];

        $newUser = $this->userService->registerUser($data);
        $this->assertInstanceOf(User::class, $newUser);
        $this->assertSame('Doe', $newUser->getName());
         
    }

    public function testRegisterUserWithNoEmail() :void {
        
          $data = [
            'username' => "Johnny",
            'email' => "",
            'name' => 'Doe',
            'firstname' => 'John',
            'password' => '12345678',
            'confirm-password' => '12345678',
            'description' => 'Bonjour, je réalise un test!',
            'createdAt' => '27.10.2025'
        ];

        try {
            $this->userService->registerUser($data);
            $this->fail("Une ValidationException aurait dû être levée !");
        } catch(ValidationException $e) {
            
            $errors = $e->getErrors();
            $this->assertArrayHasKey('email', $errors);
            $this->assertEquals("Champs Email Obligatoire", $errors['email']);

        }
        

        
    }

      public function testRegisterUserWithNull() :void {
        
          $data = [
            'username' => "Johnny",
            'email' => null,
            'name' => 'Doe',
            'firstname' => 'John',
            'password' => '12345678',
            'confirm-password' => '12345678',
            'description' => 'Bonjour, je réalise un test!',
            'createdAt' => '27.10.2025'
        ];

        try {

            $this->userService->registerUser($data);
            $this->fail("Une ValidationException aurait dû être levée !");

        } catch(ValidationException $e) {

            $errors = $e->getErrors();
            $this->assertArrayHasKey('email', $errors);
            $this->assertEquals("Champs Email Obligatoire", $errors['email']);

        }
        
    }

    

    public function testRegisterUserWithAExistingMail() :void {

        $data = [
            'username' => "Johnny",
            'email' => 'jane.doe@example.com',
            'name' => 'Doe',
            'firstname' => 'John',
            'password' => '12345678',
            'confirm-password' => '12345678',
            'description' => 'Bonjour, je réalise un test!',
            'createdAt' => '27.10.2025'
        ];

        try {

            $this->userService->registerUser($data);
            $this->fail("Une ValidationException aurait dû être levée !");

        } catch(ValidationException $e) {

            $errors = $e->getErrors();
            $this->assertArrayHasKey('email', $errors);
            $this->assertEquals("L'Adresse e-mail déjà utilisée.", $errors['email']);

        }

    }

    public function testRegisterUserWithManyErrors(): void {
        
        $data = [
            'username' => "Jane12",  // 1ere exception
            'email' => "john@doe.fr",
            'name' => null, // 2eme exception
            'firstname' => 'John',
            'password' => '1234678', // 3eme exception
            'confirm-password' => '1234567', // 4eme exception
            'description' => 'Bonjour, je réalise un test!',
            'createdAt' => '27.10.2025'
        ];

        try {

            $this->userService->registerUser($data);
            $this->fail("Une ValidationException aurait dû être levée !");
        } catch(ValidationException $e) {

            $errors = $e->getErrors();
            $this->assertArrayHasKey('username', $errors);
            $this->assertArrayHasKey('name', $errors);
            $this->assertArrayHasKey('password_length', $errors);
            $this->assertArrayHasKey('password_match', $errors);

            $this->assertEquals("Pseudo déja existant", $errors['username']);
            $this->assertEquals("Champs Nom Obligatoire", $errors['name']);
            $this->assertEquals("Le mot de passe doit contenir au moins 8 caractères.", $errors['password_length']);
            $this->assertEquals("Les mots de passe ne correspondent pas.", $errors['password_match']);
        }


    }

    public function testRegisterUserDescriptionWithHtmlTags() :void {

        $data = [
            'username' => "Johnny",
            'email' => "johndoe@mail.com",
            'name' => 'Doe',
            'firstname' => 'John',
            'password' => '12345678',
            'confirm-password' => '12345678',
            'description' => 'Bonjour Je réalise un test!',
            'createdAt' => '27.10.2025'
        ];


       $this->userService->registerUser($data);

       $users = $this->userRepo->findUserWithUsername("Johnny");
       $this->assertIsArray($users);
       $this->assertNotEmpty($users);
       $this->assertInstanceOf(User::class, $users[0]);
       $this->assertSame('Doe', $users[0]->getName());
       $this->assertSame("Bonjour Je réalise un test!", $users[0]->getDescription());

    
    }

 
}


// .\vendor\bin\phpunit.bat .\src\Tests\UserServicesTest.php