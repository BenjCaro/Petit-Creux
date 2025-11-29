<?php 

namespace Carbe\Petitcreuxv2\Templates;
use Carbe\Petitcreuxv2\Helpers\Csrf;
?>

<main class="container p-3 bg-light flex-grow-1 ">

    <h2 class="text-center mb-2 mt-2">Confiez-nous vos repas préférés! </h2>

    
        <form action="" novalidate class="form-control pb-2 border-gris bg-gris shadow-sm p-3 mt-5 mb-5 bg-body-gris rounded" style="--bs-bg-opacity: .5;">
        <input type="hidden" name="id_user" id="id_user" value="<?=$_SESSION['auth_user']['id'] ?? 0?>">
            <?php $token = Csrf::get("add_recipe");  ?>
            <input type="hidden" name="_token" value="<?= $token ?>">
        <div class="step active mb-1">
            <h3 class="text-center">Nomme ta recette et sélectionne sa catégorie : </h3>
            <div class="mb-2">
                <label for="title" class="form-label fw-bold">Titre de la recette</label>
                <input type="text" id="title" name="title" placeholder="Donner un titre à votre recette" class="form-control" required>
            </div>
            <div class="mb-2">
                <label for="id_category" class="form-label">Catégorie</label>
                <select name="id_category" id="id_category" class="form-select" required>
                    <option value="">Choisir la catégorie</option>
                <?php foreach($categories as $category): ?>
                    <option value="<?= $category->getId(); ?>"><?= htmlspecialchars($category->getName()); ?></option>
                <?php endforeach; ?>
                </select>
            </div>
            <button type="button" class="btn btn-outline-primary next">Suivant</button>
        </div>
        <div class="step">
            <h3 class="text-center">Sélectionne les ingrédients et le temps de préparation : </h3>
            <div class="mb-2">
                <label for="duration" class="form-label">Temps de préparation</label>
                <input type="text" id="duration" name="duration" placeholder="Préciser le temps de préparation" class="form-control" required>
            </div>
            <div class="mb-2">
                <label for="ingredients" class="form-label">Recherchez vos ingrédients</label>
                <div class="mb-2">
                    <button type="button" id="btnAddIngredient" class="btn btn-sm btn-outline-secondary">+ Ajouter vos ingrédients</button>
                </div>
                <div id="ingredients-container"
                    data-ingredients='<?= json_encode(array_map(function($ingredient) {
                        return [
                            "id" => $ingredient->getId(),
                            "name" => $ingredient->getName()
                        ];
                    }, $ingredients), JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>
                </div>
                <ul id="results-list" class="list-group mt-2 mb-2"></ul>
                <ul id="selected-ingredients" class="list-group mt-3"></ul> 
            </div>
            <button type="button" class="btn btn-outline-primary prev">Précédent</button>
            <button type="button" class="btn btn-outline-primary next">Suivant</button>
        </div>
        <div class="step">
            <h3 class="text-center">Rédige ici les différentes étapes de préparation : </h3>
            <div class="mb-2">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" name="description" id="description" placeholder="Décrivez les étapes de la recette" required></textarea>
            </div>
            <button type="button" class="btn btn-outline-primary prev">Précédent</button>
            <button type="submit" class="btn btn-secondary">Valider</button>
        </div>
    </form>
    
</main>
<script src="/assets/js/addRecipeForm.js" type="text/javascript"></script>
<script src="/assets/js/addIngredients.js" type="text/javascript"></script>