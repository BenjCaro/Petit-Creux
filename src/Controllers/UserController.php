<?php

namespace Carbe\Petitcreuxv2\Controllers;

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
         
    }
    
}
