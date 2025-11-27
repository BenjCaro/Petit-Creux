<?php

namespace Carbe\Petitcreuxv2\Templates;
use Carbe\Petitcreuxv2\Helpers\Flash;
?>


<main class='container p-3 bg-light'>
    <?php
     $messages = Flash::get();
     foreach($messages as $message) { ?>
        <div id="flash" class="alert alert-<?= $message['type'] ?>"><?= $message['message']?></div>
    <?php }
    ?>
    <section class="mb-4 d-flex justify-content-center">
        <form method="get" action="/search" class="w-50">
            <div class="input-group">
                <select class="form-select" name="type">
                    <option value="recipe">Recherche par Recette</option>
                    <option value="ingredient">Recherche par Ingrédient</option>
                </select>
                <input type="text" class="form-control" placeholder="Ex: Tarte aux pommes, noisettes, ..." name="q" required>
                <button class="btn btn-primary" type="submit">🔍</button>
            </div>
        </form>
   </section>
    <div class="grid mt-1 mb-3">
        <section class="favoris">
            <div class="card bg-white h-100 border border-dark" >
                <div class="card-body ">
               
                    <h2 class='card-title text-center'>Vos favoris</h2>
                    
                    <h3 class="text-center">
                        Bienvenue sur Petit Creux!
                    </h3>
                    <p>Pour profiter pleinement de la communauté Petit Creux, rejoignez-nous en cliquant <a href="/login">ici</a>.</p>
               
                </div>
            </div>
        </section>
        <section class="last_recipe">
            <div class="card bg-white h-100 border border-dark">
                <div class="card-body">
                    <h2 class='card-title'>Dernière Recette</h2>
                  
                    
                </div>
            </div>
        </section>
        <section class="popular_recipe">
            <div class="card bg-white h-100 border border-dark" >
                <div class="card-body">
                    <h2 class='card-title'>Recettes Populaires</h2>
                </div>
            </div>
        </section>
        <section class="categories text-center">
            <div class="card bg-white h-100 border border-dark" >
                <div class="card-body">
                    <h2 class='card-title'>Catégories</h2>  
                </div>
            </div>
        </section>
        <a class="btn btn-dark w-100" href="/add-recipe">Partager une recette avec nous</a>   
    </div>
</main>