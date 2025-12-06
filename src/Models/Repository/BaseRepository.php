<?php 

namespace Carbe\Petitcreuxv2\Models\Repository;
use Carbe\Petitcreuxv2\Core\Database;

use PDO;

/** @phpstan-consistent-constructor */
class BaseRepository {

    protected PDO $pdo;
    protected string $table;
    protected string $modelClass;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnect();
    }

    public function findById(int $id) :?static
 {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
        $object = new $this->modelClass();
        $object->hydrate($data);
        return $object;
    }

    return null;


    }

    public function getPdo() :PDO  {
        return $this->pdo;
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
            
            $object = new $this->modelClass();
            $object->hydrate($data);
            $objects[] = $object;  
        }

        return $objects;

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


    public function delete(int $id) : bool { // supprimer une donnée (ex: un utilisateur, une recette, un commentaire, etc...)

        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} where id = :id");
       
        return $stmt->execute(['id' => $id]);
    }

    

}