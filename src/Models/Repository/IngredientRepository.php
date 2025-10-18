<?php 

namespace Carbe\App\Models;

use Carbe\App\Models\Repositories\BaseRepository;
use PDO;

class IngredientRepository extends BaseRepository {

    protected string $table = 'ingredients';

/**
 * @param array<string, mixed> $data
 */

    public function __construct() {
        
      parent::__construct();
    }


/**
 * @return array<string, mixed>|false
 * 
 */

 public function getIngredientName(string $name) :array|false {
     
          $stmt= $this->pdo->prepare("SELECT
          ingredients.id, ingredients.name, ingredients.type
          FROM ingredients 
          WHERE ingredients.name = :name");

          $stmt->execute(['name' => $name]);
          $results = $stmt->fetch(PDO::FETCH_ASSOC);

          return $results ?: false;
     }

 public function findIngredient(string $name): array {
              
     if (trim($name) === '') {
                     return [];
     }

     $stmt = $this->pdo->prepare("SELECT
               ingredients.id, ingredients.name, ingredients.type
               FROM ingredients 
               WHERE ingredients.name LIKE :name
               ORDER BY ingredients.name ASC
          ");
     $stmt->execute([':name' => "%$name%"]);

     return $stmt->fetchAll(PDO::FETCH_ASSOC); }

}