<?php

namespace Carbe\Petitcreuxv2\Core;

use PDO;
use Exception;

class Database {
    
    private static ?Database $instance = null;
    private PDO $pdo;

    private function __construct(?PDO $pdo = null) {

        if ($pdo) {

            $this->pdo = $pdo;
            return;
        }

        try {
            
            $host = getenv('DB_HOST');
            $dbname = getenv('DB_DATABASE');
            $user = getenv('DB_USERNAME');
            $pass = getenv('DB_PASSWORD');

            $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
            $this->pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);

        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public static function setInstance(Database $db): void {
        self::$instance = $db;
    }

    public function getConnect(): PDO {
        return $this->pdo;
    }
}


// $pdo = Database::getInstance()->getConnect();