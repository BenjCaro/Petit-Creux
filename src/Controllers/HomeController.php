<?php

namespace Carbe\Petitcreuxv2\Controllers;

class HomeController extends BaseController {

    public function viewHomePage() :void {
        // 
        
        $this->render("index", [
            "title" => 'Petit Creux | Bienvenue'
        ]);
    }
}