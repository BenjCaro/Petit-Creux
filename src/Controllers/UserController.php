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
        
        $old = $_SESSION['old'] ?? [];
        unset($_SESSION['old']);
        $this->render("register", [
            'title' => 'Petit Creux | Inscription',
            'old' => $old
        ]);
    }

    /**
     * Page de connexion
     * 
     */

    public function loginForm() :void {

        $old = $_SESSION['old'] ?? [];
        unset($_SESSION['old']);
        $this->render("login", [
            'title' => 'Petit Creux | Connexion',
            'old' => $old
        ]);

    }

    /**
     * 
     * register() traite l'inscription en faisant appel au UserServices::registerUser()
     * 
     * 
     */

    public function register() :void {

        
        $_SESSION['old'] = $_POST;

        try {

          $this->userServices->registerUser($_POST);
          Flash::set("Inscription Réussie", "primary");
          header("location: /");
          exit;

        } catch(ValidationException $e) {

            foreach($e->getErrors() as $field => $message) {
                Flash::setErrorsForm($field, $message);
            }
            header("location: /register");
            exit;

        }
         
    }


    /**
     *  Connexion utilisateur 
     * 
     */

    public function connexion() :void {
       
        
        $email = $_POST['email'];
        $token = $_POST['_token'];
        $password = $_POST['password'];
        $_SESSION['old'] = $email;

        try {

            $this->userServices->logIn($token, $email, $password);
            Flash::set("Connexion Réussie.", "success");
            header("Location: /");
            exit;

        } catch(ValidationException $e) {
            foreach($e->getErrors() as $field => $message) {
                Flash::setErrorsForm($field, $message);
            }
            header("location: /login");
            exit;
        }
    }
    
}
