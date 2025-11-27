<?php

namespace Carbe\Petitcreuxv2\Controllers;
use Carbe\Petitcreuxv2\Exceptions\ValidationException;
use Carbe\Petitcreuxv2\Helpers\Flash;
use Carbe\Petitcreuxv2\Helpers\Auth;
use Carbe\Petitcreuxv2\Models\Entites\Recipe;
use Carbe\Petitcreuxv2\Services\RecipeService;

class RecipeController extends BaseController {

    private RecipeService $recipeService;

    public function __construct(RecipeService $recipeService)
    {
        $this->recipeService = $recipeService;
    }

    public function NewRecipeForm() :void {
       
        $this->render("addRecipe", [
            "title" => "Petit Creux | Création de recette"
        ]);
    }

    public function newRecipe() :void {

        try {
            $this->recipeService->createRecipe($_POST);
            Flash::set("Recette enregistrée", "primary");
            header("location: /");
            exit;

        } catch(ValidationException $e) {
            foreach($e->getErrors() as $field => $message) {
                Flash::setErrorsForm($field, $message);
            }
            header("Location: /add-recipe");
            exit;
        }   
    }

}