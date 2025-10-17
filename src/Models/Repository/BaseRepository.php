<?php 

namespace Carbe\App\Models\Repositories;
use Carbe\Petitcreuxv2\Core\Database;

use PDO;

/** @phpstan-consistent-constructor */
class BaseRepository {

    protected PDO $pdo;
    protected string $table;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnect();;
    }

    public function findById(int $id) :?static
 {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
        $object = new static($this->pdo); // instancie la classe enfant qui appelle findById
        $object->hydrate($data);
        return $object;
    }

    return null;


    }

 /**
 * @return static[]
 */


    public function findAll() :array {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table}");
        $stmt->execute();
        $result= $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        $objects = [];

        foreach($result as $data) {
            
            $object = new static($this->pdo); 
            $object->hydrate($data);
            $objects[] = $object;  
        }

        return $objects;

    }

/**
 *  @param array<string, mixed> $data
 * 
 *  Ne sera plus utilisée à la fin de la refactorisation totale du projet 
 */


public function insert(array $data) :void {
        $array = array_keys($data);
        $columns = implode( ", " , $array);
        $values = ':' . implode(', :', $array);

        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} ($columns) VALUES ($values)"); 
        $stmt->execute($data);

    }   

/**
 *  @param array<string, mixed> $data
 */

    public function update(int $id, array $data): void {
        
   
    $fields = [];
    foreach ($data as $column => $value) {
        $fields[] = "$column = :$column";
    }

    $setClause = implode(', ', $fields);
    $stmt = $this->pdo->prepare("UPDATE {$this->table} SET $setClause WHERE id = :id");
    $data['id'] = $id;
    $stmt->execute($data);
}



/**
 *  @param array<string, mixed> $data
 */


    public function hydrate(array $data): void {

        foreach ($data as $key => $value) {
            $camelCaseKey = lcfirst(str_replace('_', '', ucwords($key, '_')));
            $method = 'set' . ucfirst($camelCaseKey);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    public function delete(int $id) : bool { // supprimer une donnée (ex: un utilisateur, une recette, un commentaire, etc...)

        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} where id = :id");
       
        return $stmt->execute(['id' => $id]);
    }

    

}