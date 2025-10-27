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

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("Adresse Email invalide!");
        $this->userService->registerUser($data);

        
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

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("Adresse Email invalide!");
        $this->userService->registerUser($data);
    }

    public function testRegisterUserLess8charactersPwd() :void {
        
          $data = [
            'username' => "Johnny",
            'email' => "johndoe@mail.com",
            'name' => 'Doe',
            'firstname' => 'John',
            'password' => '1234567',
            'confirm-password' => '1234567',
            'description' => 'Bonjour, je réalise un test!',
            'createdAt' => '27.10.2025'
        ];

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("Le mot de passe doit contenir au moins 8 caractères.");
        $this->userService->registerUser($data);
    }

    public function testRegisterUserDifferentConfirmedPwd() :void {
        
          $data = [
            'username' => "Johnny",
            'email' => "johndoe@mail.com",
            'name' => 'Doe',
            'firstname' => 'John',
            'password' => '12345678',
            'confirm-password' => '12345679',
            'description' => 'Bonjour, je réalise un test!',
            'createdAt' => '27.10.2025'
        ];

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("Les mots de passe ne correspondent pas.");
        $this->userService->registerUser($data);
    }

    public function testRegisterUserWithExistingMail() :void {

        $data = ['username' => 'Jane123',
                'name' => 'Doe',
                'firstname' => 'Jane',
                'email' => 'jane.doe@example.com',
                'description' => 'Bonjour, je réalise un test!',
                'password' => 'password123',
                'confirm-password' => 'password123',
                'role' => 'user',
                'createdAt' => '27.10.2025'];

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("L'Adresse e-mail déjà utilisée.");
        $this->userService->registerUser($data);

    }


    public function testRegisterUserWithExistingUsername() :void {

        $data = ['username' => 'Jane12',
                'name' => 'Doe',
                'firstname' => 'Jane',
                'email' => 'jane12.doe@example.com',
                'description' => 'Bonjour, je réalise un test!',
                'password' => 'password123',
                'confirm-password' => 'password123',
                'role' => 'user',
                'createdAt' => '27.10.2025'];

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("Pseudo déja existant");
        $this->userService->registerUser($data);

    }


    public function testRegisterUserWithNullUsername() :void {

        $data = ['username' => null,
                'name' => 'Doe',
                'firstname' => 'Jane',
                'email' => 'jane12.doe@example.com',
                'description' => 'Bonjour, je réalise un test!',
                'password' => 'password123',
                'confirm-password' => 'password123',
                'role' => 'user',
                'createdAt' => '27.10.2025'];

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("Champs Pseudo Obligatoire!");
        $this->userService->registerUser($data);

    }


     
}


// .\vendor\bin\phpunit.bat .\src\Tests\UserServicesTest.php