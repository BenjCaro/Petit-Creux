<?php 

namespace Carbe\Petitcreuxv2\Models\Repository;

use Carbe\Petitcreuxv2\Models\Entites\Category;
use Carbe\Petitcreuxv2\Models\Repository\BaseRepository;
use PDO;

class CategoryRepository extends BaseRepository {
    
    protected string $table = "categories";


  /**
 * @param array<string, mixed> $data
 */


    public function __construct() {
        
      parent::__construct();
    }


  public function save(Category $category) :bool {
     $stmt = $this->pdo->prepare("INSERT INTO categories (name, slug)
     VALUES (:name, :slug)");
     
     return $stmt->execute([

        'name' => $category->getName(),
        'slug' => $category->getSlug()
     ]);
  }

/**
 * @return array<string, mixed>|false
 * 
 */

public function getCatByName(string $name) :array|false
 {
    
     $stmt = $this->pdo->prepare('SELECT categories.id, categories.name, categories.slug
      FROM `categories` 
      WHERE categories.name = :name');
      $stmt->execute(['name' => $name]);
      $results= $stmt->fetch(PDO::FETCH_ASSOC);

      return $results ?: false;

 }
 
/**
 * @return Category|null
 */


 public function getCatBySlug(string $slug) :?Category {
    
     $stmt = $this->pdo->prepare('SELECT categories.id, categories.name, categories.slug
      FROM `categories` 
      WHERE categories.slug = :slug');
      $stmt->execute(['slug' => $slug]);
      $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
        return null; 
    }

      $category = new Category($data);
      return $category;
 }


 /**
  * 
  * @return Category[]
  */

public function searchCategoryWithName($search) :array {
    $stmt = $this->pdo->prepare("
         SELECT categories.name,
         COUNT(recipes.id) AS total_recipes
         FROM categories
         LEFT JOIN recipes ON recipes.id_category = categories.id
         WHERE name LIKE :search
         GROUP BY categories.name;
    ");

    $stmt->execute(['search' => "%$search%"]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $categories = [];

    foreach($results as $data) {
       $category = new Category($data);
       $category->setTotalRecipes($data['total_recipes']);
       $categories[] = $category;

    }

    return $categories;

}


/**
 * Méthode permettant d'afficher le nombre de recettes par catégorie
 * LEFT JOIN permet d'afficher les catégories sans recette
 * @return Category[]
 */

public function countRecipesByCat() :array {
      $stmt = $this->pdo->prepare("
         SELECT categories.name, categories.slug, categories.image,
         COUNT(recipes.id) AS total_recipes
         FROM categories
         LEFT JOIN recipes ON recipes.id_category = categories.id
         GROUP BY categories.id, categories.name;
      ");
      $stmt->execute();

      $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $categories = [];

      foreach($results as $data) {
        
        $category = new Category($data);
        $categories[] = $category;

      }

      return $categories;


}

}