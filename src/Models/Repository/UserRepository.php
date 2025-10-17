<?php 

namespace Carbe\App\Models\Repositories;
use Carbe\App\Models\Repositories\RecipeModel;
use Carbe\App\Models\Entities\User;
use Carbe\App\Models\Entities\Recipe;
use Carbe\App\Models\Entities\Category;
use Carbe\App\Models\Repositories\BaseRepository;
use PDO;

class UserRepository extends BaseRepository  {
   
/**
* @param array <string, mixed> $data
*/
 protected string $table = 'users';
 public function __construct() {
      
    parent::__construct();
    
    }

public function findUserWithEmail(string $email) :?User {
    $stmt = $this->pdo->prepare('SELECT id, name, firstname, password, role FROM users WHERE email = :email');
    $stmt->execute([
      'email' => $email
    ]);

  $result =$stmt->fetch(PDO::FETCH_ASSOC);

     if (!$result) {
        return null;
    }

  return new User($result);

}


/**
 * @return RecipeModel[]
 */

public function getFavoris(int $userId) :array {
      $stmt = $this->pdo->prepare("SELECT users.id, users.name AS user_name, firstname, email, role, favoris.id_user, recipes.*, categories.name AS category_name
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
    $stmt = $this->pdo->prepare('SELECT users.id, users.name, users.firstname, users.email, users.role, users.createdAt 
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
 * @return Usermodel[]|null
 * 
 */

  public function findUserWithName(string $search) :?array {
      $stmt = $this->pdo->prepare('
      SELECT id, name, firstname, email, role, createdAt
      FROM users
      WHERE LOWER(name) LIKE LOWER(:search) OR LOWER(firstname) LIKE LOWER(:search)
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

     
      $stmt = $this->pdo->prepare("INSERT INTO users (name, firstname, email, password, role, description, created_at)
          VALUES (:name, :firstname, :email, :password, :role, :description, :created_at) ");

      return  $stmt->execute([
            'name' => $user->getName(),
            'firstname' => $user->getFirstname(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'role' => $user->getRole(),
            'description' => $user->getDescription(),
            'created_at' => $user->getCreatedAt(),
        ]);
  }

}

