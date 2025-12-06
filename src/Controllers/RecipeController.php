<?php

namespace Carbe\Petitcreuxv2\Controllers;
use Carbe\Petitcreuxv2\Exceptions\ValidationException;
use Carbe\Petitcreuxv2\Helpers\Flash;
use Carbe\Petitcreuxv2\Helpers\Auth;
use Carbe\Petitcreuxv2\Models\Entites\Recipe;
use Carbe\Petitcreuxv2\Models\Repository\CategoryRepository;
use Carbe\Petitcreuxv2\Services\RecipeService;

class RecipeController extends BaseController {

    private RecipeService $recipeService;

    public function __construct(RecipeService $recipeService)
    {
        $this->recipeService = $recipeService;
    }

    public function NewRecipeForm() :void {

        $categorieRepo = new CategoryRepository();
        $categories = $categorieRepo->findAll();

        $this->render("addRecipe", [
                "title" => "Petit Creux | Création de recette",
                "categories" => $categories
            ]);

        // if(Auth::isAuth()) {
        //         $this->render("addRecipe", [
        //         "title" => "Petit Creux | Création de recette"
        //     ]);
        // } 
        // else {
        //     Flash::set("Connectez vous pour partager votre recette", "secondary");
        //     header("location: /login");
        //     exit;
        // }
        
        
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