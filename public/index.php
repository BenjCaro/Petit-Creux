<?php

use Carbe\Petitcreuxv2\Core\Database;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();


$db = Database::getInstance()->getConnect();

$stmt = $db->query('SELECT NOW()');
echo 'Connexion OK : ' . $stmt->fetchColumn();