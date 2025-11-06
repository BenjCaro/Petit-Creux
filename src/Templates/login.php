<?php

namespace Carbe\Petitcreuxv2\Templates;
use Carbe\Petitcreuxv2\Helpers\Csrf;
use Carbe\Petitcreuxv2\Helpers\Flash;
?>

<main class='container p-3 bg-light'>
    <?php
     $messages = Flash::get();
     foreach($messages as $message) { ?>
        <div id="flash" class="alert alert-<?= $message['type'] ?>"><?= $message['message']?></div>
    <?php }
    ?>
    <h2 class="text-center mb-2">Connexion</h2>  
    <div class="d-flex flex-column w-25 m-auto">
        <form method="post" action="/login" class="form-control p-3 border-gris bg-gris shadow-sm p-3 mb-5 bg-body-gris rounded" style="--bs-bg-opacity: .5;">
            <?php $token = Csrf::get("login");  ?>
            <input type="hidden" name="_token" value="<?= $token ?>">
            <div class="mb-3 pt-2">
                <label for="email"  class="form-label">Email</label>
                <input type="email" value="<?=  $old['email'] ?? ''   ?>" name="email" id="email" class="form-control" required>
            </div>
            <div class="mb-3">
            <label for="password"  class="form-label">Mot de passe</label> 
                <input type="password" name="password" id="password" class="form-control"  required> 
            </div>
            <div class="d-flex flex-column">
                <button class="btn btn-primary" type="submit">Se Connecter</button>
                <span class="text-center">ou</span>
                <button class="btn btn-secondary"><a class="nav-link" href="/register">Rejoindre Petit Creux!</a></button>
            </div>
            
        </form>
    </div>
</main>