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
            'title' => 'Petit Creux | Inscription'
        ]);
    }

    /**
     * Page de connexion
     * 
     */

    public function loginForm() :void {

        $this->render("/login", [
            'title' => 'Petit Creux | Connexion'
        ]);

    }

    /**
     * 
     * register() traite l'inscription en faisant appel au UserServices::registerUser()
     * 
     * 
     */

    public function register() :void {

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


    /**
     *  Connexion par un utilisateur 
     * 
     */

    public function connexion() :void {
        session_start();
        $email = $_POST['email'];
        $token = $_POST['token'];
        $password = $_POST['password'];
        $_SESSION['old'] = $email;

        try {

            $this->userServices->logIn($token, $email, $password);
            Flash::set("Connexion Réussie.", "success");
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
