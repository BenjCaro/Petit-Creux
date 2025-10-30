<?php

namespace Carbe\Petitcreuxv2\Controllers;

use Carbe\Petitcreuxv2\Exceptions\ValidationException;
use Carbe\Petitcreuxv2\Services\UserServices;
use Carbe\Petitcreuxv2\Helpers\Flash;

class UserController extends BaseController {
    
    private UserServices $userServices;
    
    public function __construct(UserServices $userServices) {
       
        $this->userServices = $userServices;
        
    }

    /**
     * registerForm() envoi la vue du formulaire d'inscription
     * 
     */

    public function registerForm() :void {
        
        $this->render("/register", [
            'title' => 'Petit Creux |Inscription'
        ]);
    }

    /**
     * 
     * register() traite l'inscription en faisant appel au UserServices::registerUser()
     * 
     * 
     */

    public function register() {

        session_start();
        $_SESSION['old'] = $_POST;

        try {

          $this->userServices->registerUser($_POST);
          Flash::set("Inscription Réussie", "success");
          header("location: /home");
          exit;

        } catch(ValidationException $e) {

            foreach($e->getErrors() as $field => $message) {
                Flash::setErrorsForm($field, $message);
            }
            header("location: /register");
            exit;

        }
         
    }
    
}
