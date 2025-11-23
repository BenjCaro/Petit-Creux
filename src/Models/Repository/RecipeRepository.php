<?php 

namespace Carbe\Petitcreuxv2\Models\Repository;


use Carbe\Petitcreuxv2\Models\Entites\Ingredient;
use Carbe\Petitcreuxv2\Models\Entites\RecipeIngredient;
use Carbe\Petitcreuxv2\Models\Repository\BaseRepository;
use Carbe\Petitcreuxv2\Models\Entites\Recipe;
use Carbe\Petitcreuxv2\Models\Entites\User;
use Carbe\Petitcreuxv2\Models\Entites\Category;
use PDO;



class RecipeRepository extends BaseRepository {


 protected string $table = 'recipes';
 public function __construct()
    {
        parent::__construct();
    }

public function createRecipe(Recipe $recipe) :bool {

    $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (title, slug, id_user, id_category, createdAt, duration)
     VALUES (:title, :slug, :id_user, :id_category, :createdAt, :duration)");

    return $stmt->execute([
        'title' => $recipe->getTitle(),
        'slug' => $recipe->getSlug(),
        'id_user' => $recipe->getIdUser(),
        'id_category' => $recipe->getIdCategory(),
        'createdAt' => $recipe->getCreatedAt(),
        'duration' => $recipe->getDuration()
        
    ]);

    $id = (int)$this->pdo->lastInsertId();
    $recipe->setId($id);

    return true;

}

public function existRecipeWithTitle(string $title) :bool  {
    
    $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM recipes WHERE title LIKE :title");
    $stmt->execute([
        'title' => "%$title%"
    ]);

    return (int)$stmt->fetchColumn() > 0;
    
}

 }

