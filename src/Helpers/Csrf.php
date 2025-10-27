<?php 

namespace Carbe\Petitcreuxv2\Helpers;


class Csrf {

    /**
     * Génère un token CSRF pour protéger contre le Cross-Site Request Forgery
     * 
     * @param string $formName correspond au nom du formulaire
     * @return string un token enregistré dans la session
     * 
     */

    public static function get(string $formName) :string {
       
       
        if (!isset($_SESSION['csrf_tokens']) || !is_array($_SESSION['csrf_tokens'])) {
            $_SESSION['csrf_tokens'] = []; 
        }

        if(!isset($_SESSION['csrf_tokens'][$formName])) {
            $_SESSION['csrf_tokens'][$formName] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['csrf_tokens'][$formName];
    }

    /**
     * Effectue la comparaison entre le token enregistré dans la base et le token envoyé par le formulaire
     * 
     * @param string $formName Nom du formulaire
     * @param string $token le token envoyé par le formulaire
     * @param string $view page de redirection si le verification échoue
     * 
     */

    public static function check(string $formName,string $token, string $view) :void {

        if(empty($token) || $_SESSION['csrf_tokens'][$formName] !== $token) {
          Flash::set("Erreur survenue.", "secondary");
          header("Location: $view");
          exit;
        }
    }

    public static function isValid(string $formName, string $token): bool
    {
    return !empty($token) 
        && isset($_SESSION['csrf_tokens'][$formName]) 
        && $_SESSION['csrf_tokens'][$formName] === $token;
    }


}