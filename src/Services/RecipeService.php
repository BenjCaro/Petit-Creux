<?php  

namespace Carbe\Petitcreuxv2\Services;

use Carbe\Petitcreuxv2\Models\Entites\Recipe;
use Carbe\Petitcreuxv2\Models\Repository\RecipeRepository;

class RecipeService {

    private RecipeRepository $recipeRepo;

    public function __construct(RecipeRepository $recipeRepo)
    {
        $this->recipeRepo = $recipeRepo;
    }

    public function createRecipe(array $data) :?Recipe {

        
    }


}