<?php

namespace Carbe\Petitcreuxv2\Templates;
use Carbe\Petitcreuxv2\Helpers\Csrf;
use Carbe\Petitcreuxv2\Helpers\Flash;
?>

<main class='container p-3 bg-light border-end border-start border-secondary'>
    <h2 class="text-center mb-4">
        Inscription
    </h2>
    <?php
     $messages = Flash::get();
     foreach($messages as $message) { ?>
        <div id="flash" class="alert alert-<?= $message['type'] ?>"><?= $message['message']?></div>
    <?php }
    ?>
    <div class="d-flex flex-column w-50 m-auto">
        <form action="/register" method="POST" class="form-control pb-2 border-gris bg-gris shadow-sm p-3 mb-5 bg-body-gris rounded" style="--bs-bg-opacity: .5;">
            <?php $token = Csrf::get("submit");?>
            <input type="hidden" name="_token" value="<?= $token ?>">
             <div class="mb-3 pt-2">
                    <label for="username" class="form-label text-cacao fw-bold">Pseudo *</label>
                    <input type="text" id="username" name="username" class="form-control" value="<?= $old['username'] ?? ''  ?>" required>
                    
            </div>
            <div class="mb-3 pt-2">
                    <label for="name" class="form-label text-cacao fw-bold">Nom *</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?= $old['name'] ?? ''  ?>" required>
                    
            </div>
            <div class="mb-3 pt-2">
                    <label for="firstname" class="form-label text-cacao fw-bold">Prénom *</label>
                    <input type="text" id="firstname" name="firstname" class="form-control" value="<?= $old['name'] ?? ''  ?>" required>
                  
            </div>
            <div class="mb-3 pt-2">
                    <label for="email" class="form-label text-cacao fw-bold">Email *</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?= $old['email'] ?? ''   ?>" required>
                   
            </div>
            <div class="mb-3 pt-2">
                    <label for="password" class="form-label text-cacao fw-bold">Mot de passe *</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                   
            </div>
            <div class="mb-3 pt-2">
                    <label for="confirm-password" class="form-label text-cacao fw-bold">Confirmez votre mot de passe *</label>
                    <input type="password" id="confirm-password" name="confirm-password" class="form-control" required>
                    
            </div> 
            <div class="mb-3 pt-2">
                <label for="description" class="form-label text-cacao fw-bold">Décris toi!</label>
                <textarea name="description" id="description" class="form-control fst-italic" placeholder="J'aime la communauté Petit Creux!"></textarea>

            </div>
            <div class="text-center mb-2">
                <button class="btn btn-secondary w-25" type="submit">Valider</button>
            </div>
            <small class="fst-italic">* champs obligatoires</small>

        </form>
    </div>
</main>