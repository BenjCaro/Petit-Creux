<?php

use PHPUnit\Framework\TestCase;
use Carbe\Petitcreuxv2\Core\Database;
use Carbe\Petitcreuxv2\Services\UserServices;
use Carbe\Petitcreuxv2\Models\Entites\User;
use Carbe\Petitcreuxv2\Models\Repository\UserRepository;

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
     
}


// .\vendor\bin\phpunit.bat .\src\Tests\UserServicesTest.php