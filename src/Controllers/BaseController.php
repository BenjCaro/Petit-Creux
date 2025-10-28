<?php

namespace Carbe\Petitcreuxv2\Controllers;
use Carbe\Petitcreuxv2\Services\UserServices;

class BaseController {
    
    protected function render(string $view, array $data = []) :void {

        extract($data);

            require_once VIEW_PATH . '/Partials/header.php';
            require_once VIEW_PATH . '/Partials/banniere.php';
            require_once $view;
            require_once VIEW_PATH . '/Partials/modal.php';
            require_once VIEW_PATH . '/Partials/footer.php';

    }

/** 
*  
* Methode permettant de s'assurer que l'id de l'utilisateur connecté correspond à l'id utilisateur créateur de la recette,
* d'un commentaire. 
* Utile pour contrôler si l'utilisateur est habilité à des actions CRUD.
*/

//     protected function checkUser(int $resourceOwnerId): void {
//     session_start();

//     if ($resourceOwnerId !== $_SESSION['auth_user']['id']) {
//         $_SESSION['errors'][] = "Action non autorisée.";
//         header('Location: /mon-compte');
//         exit;
//     }
// }

}