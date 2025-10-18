<?php 

namespace Carbe\Petitcreuxv2\Models\Repository;


use Carbe\Petitcreuxv2\Models\Repository\BaseRepository;


class RecipeIngredientRepository extends BaseRepository {

    protected string $table= "recipes_ingredients";
    
/**
 * @param array<string, mixed> $data
 */
    public function __construct() {
        parent::__construct();
    }
    
    public function deleteByRecipeId(int $idRecipe): bool {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id_recipe = :id_recipe");
        return $stmt->execute(['id_recipe' => $idRecipe]);
    
    }

    public function removeIngredient(int $idIngredient) :bool {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id_ingredient = :id_ingredient");
        return $stmt->execute(['id_ingredient'=> $idIngredient]);
    }

}