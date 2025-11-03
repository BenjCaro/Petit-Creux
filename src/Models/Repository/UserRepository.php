<?php 

namespace Carbe\Petitcreuxv2\Models\Repository;

use Carbe\Petitcreuxv2\Models\Entites\User;
use Carbe\Petitcreuxv2\Models\Entites\Recipe;
use Carbe\Petitcreuxv2\Models\Entites\Category;
use Carbe\Petitcreuxv2\Models\Repository\BaseRepository;
use PDO;
use InvalidArgumentException;

class UserRepository extends BaseRepository  {
   
/**
* @param array <string, mixed> $data
*/
 protected string $table = 'users';
 public function __construct() {
      
    parent::__construct();
    
    }

    /**
     * findUser sert à trouver
     * 
     * @return User || null 
     * @param $field correspond au champs SQL de la table User (ex: email, username)
     * @param $value correspond à la donnée du champs correspondant (ex: johndoe@mail.com)
     * 
     */

public function findUser(string $field, string $value) :?User {

    $fields = ["username", "email"];
    if (!in_array($field, $fields)) {
      throw new InvalidArgumentException("Champs invalide!");
    }
    
    
    $stmt = $this->pdo->prepare("SELECT id, username, name, firstname, email, password, role FROM users WHERE $field = :value");
    $stmt->execute([
      'value' => $value
    ]);

    $result =$stmt->fetch(PDO::FETCH_ASSOC);

     if (!$result) {
        return null;
    }

    return new User($result);
}


/**
 * @return Recipe[]
 */

public function getFavoris(int $userId) :array {
      $stmt = $this->pdo->prepare("SELECT users.id, users.username, users.name AS user_name, firstname, email, role, favoris.id_user, recipes.*, categories.name AS category_name
                                  FROM users
                                  JOIN favoris ON favoris.id_user = users.id
                                  JOIN recipes ON favoris.id_recipe = recipes.id
                                  JOIN categories ON categories.id = recipes.id_category
                                  WHERE users.id = :id AND recipes.state = 'published'");

     $stmt->execute([
        'id' => $userId
      ]);

      $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if (!empty($data)) {
        $this->hydrate([
            'id' => $data[0]['id'], 
            'username' => $data[0]['username'],
            'name' => $data[0]['user_name'], 
            'firstname' => $data[0]['firstname'],
            'email' => $data[0]['email'],
            'role' => $data[0]['role'],
        ]);
    }
     
      $recipes = [];

      foreach($data as $row) {
        $recipe = new Recipe([
        'id' => $row['recipe_id'],
        'title' => $row['title'],
        'slug' => $row['slug'],
        'duration' => $row['duration'],
        'description' => $row['description']
    ]);
         
       $category = new Category(['name' => $row['category_name']]);
      
        
        $recipe->setCategory($category);
        $recipes[] = $recipe;

      }

      return $recipes;
  }

/**
 * getAllUsers pour recuperer tout les utilisateurs de Petit Creux dont le role est user 
 * 
 * @return User[]
 */
 
public function getAllUsers() :?array {
    $stmt = $this->pdo->prepare('SELECT users.id, users.username, users.name, users.firstname, users.email, users.role, users.createdAt 
            FROM users 
            WHERE users.role = "user"
            ORDER BY createdAt ');
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if(!$results) {
      return null;

    }

    $users = [];

    foreach($results as $result) {
       $user = new User($result);
       $users[] = $user;
    }

    return $users;
  }

/**
 * @return User[]|null
 * 
 */

  public function findUserWithUsername(string $search) :array {
      $stmt = $this->pdo->prepare('
      SELECT id, username, name, firstname, email, role, description, createdAt
      FROM users
      WHERE LOWER(username) LIKE LOWER(:search)
  ');
  $stmt->execute(['search' => "%$search%"]);


    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
     
    $users = [];

    foreach($results as $data) {
      $user = new User($data);
      $users[] = $user;
    }
    
    return $users;

  }

  /**
   * 
   *  createUser(); 
   * 
   * 
   */

  public function createUser(User $user) : bool {

     
      $stmt = $this->pdo->prepare("INSERT INTO users (username, name, firstname, email, password, role, description, createdAt)
          VALUES (:username, :name, :firstname, :email, :password, :role, :description, :createdAt) ");

      return  $stmt->execute([
            'username' => $user->getUsername(),
            'name' => $user->getName(),
            'firstname' => $user->getFirstname(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'role' => $user->getRole(),
            'description' => $user->getDescription(),
            'createdAt' => $user->getCreatedAt(),
        ]);
  }

}

