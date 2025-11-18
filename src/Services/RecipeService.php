<?php  

namespace Carbe\Petitcreuxv2\Services;

use Carbe\Petitcreuxv2\Helpers\Csrf;
use Exception;
use Carbe\Petitcreuxv2\Models\Entites\Recipe;
use Carbe\Petitcreuxv2\Models\Entites\RecipeIngredient;
use Carbe\Petitcreuxv2\Models\Repository\RecipeRepository;
use Carbe\Petitcreuxv2\Exceptions\ValidationException;
use Carbe\Petitcreuxv2\Models\Entites\Description;
use Carbe\Petitcreuxv2\Models\Repository\DescriptionRepository;
use Carbe\Petitcreuxv2\Models\Repository\RecipeIngredientRepository;

class RecipeService {

    private RecipeRepository $recipeRepo;
    private RecipeIngredientRepository $recipeIngredientRepo;
    private DescriptionRepository $descriptionRepo;

    public function __construct(RecipeRepository $recipeRepo, RecipeIngredientRepository $recipeIngredientRepo, DescriptionRepository $descriptionRepo)
    {
        $this->recipeRepo = $recipeRepo;
        $this->recipeIngredientRepo = $recipeIngredientRepo;
        $this->descriptionRepo = $descriptionRepo;
    }

    public function createRecipe(array $data) //:?Recipe 
    {
        $token = $data['_token'];
        Csrf::check('addRecipeForm', $token, '/add-recipe');

        $title = trim($data['title']);
        $slug = trim($data["slug"]);
        $idUser = $data['id_user'];
        $idCategory = $data['id_category'];
        $duration = trim($data['duration']);

        // RecipeIngredient

        $ingredients = $data['ingredients'];
        $quantity= $data['quantites'];
        $unit = $data['unit'];
        
        // Description 

        $steps = $data['step_number'];
        $texte = trim($data['texte']);

        $errors = [];

        if(empty($title)) {
            $errors['title'] = "Veuillez donner un titre à la recette.";
        }

        if(empty($idUser)) {
            $errors['id_user'] = 'Veuillez vous reconnecter à votre compte.';
        }

        if(empty($idCategory)) {
            $errors['id_category'] = 'Sélectionner une catégorie pour votre recette.';
        }

        if(empty($duration)) {
            $errors['duration']= "Veuillez préciser une durée de préparation pour votre recette.";
        }

        if(empty($ingredients) || empty($quantity) || empty($unit)) {
            $errors['ingredients'] = "Veuillez selectionner un ingrédient, sa quantité et l'unité.";
        }

        if(empty($steps)) {
            $errors['step_number'] = "Veuillez préciser le numéro de l'étape de la recette.";
        }

        if(empty($texte)) {
            $errors['texte'] = "Veuillez rédiger l'étape de la recette.";
        } 


        if(!empty($errors)) {
            throw new ValidationException($errors);
        }


        // convertir le titre en slug

        $recipeData = [
          'title' => $title,
          'slug' => $slug, 
          'id_user' => $idUser,
          'id_category' => $idCategory,
          'duration' => $duration
     ];


        // Insertion en BDD sur 3 tables liées par id_recipe
        $pdo = $this->recipeRepo->getPdo();

        try {

           $pdo->beginTransaction();
           $recipe = new Recipe($recipeData);
           $this->recipeRepo->createRecipe($recipe);
           $idRecipe = $pdo->lastInsertId();

            foreach($ingredients as $index => $idIgredient) {
                $recipeIngredients = new RecipeIngredient([
                    'id_recipe' => $idRecipe,
                    'id_ingredient' => $idIgredient,
                    'quantity' => $quantity[$index],
                    'unit' => $unit[$index]
                ]);

             $this->recipeIngredientRepo->createRecipeIngredient($recipeIngredients);
            }

            foreach($steps as $index => $step) {
                $description = new Description([
                    'step_number' => $step,
                    'texte' => $texte[$index],
                    'id_recipe' => $idRecipe
                ]);
            
                $this->descriptionRepo->createDescriptionRecipe($description);
            }
            

           $pdo->commit();

        } catch(Exception $e) {
            $pdo->rollBack();
        }

        
     }


}