<?php

namespace Carbe\Petitcreuxv2\Helpers;

class Auth {

    /**
     * Ouvre une session 
     * S'assure que l'utilisateur doit etre connecté pour accéder à certaines pages sinon
     * Effectue une redirection du visiteur sur la page de connexion/creation de compte
     */

       public static function isAuth(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['auth_user'])) {
            
            Flash::set("Connectez-vous pour accèder à cette page!", "secondary");
            header('Location: /login');
            exit();
        }
    }

   public static function viewAuth(): bool
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    return isset($_SESSION['auth_user']);
}


    public static function isAdmin(): void {
         if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (
            !isset($_SESSION['auth_user']) || 
            !isset($_SESSION['auth_user']['role']) || 
            $_SESSION['auth_user']['role'] !== 'admin' 
            ) {
            
            Flash::set("Vous ne possédez pas les autorisations pour accéder à cette page", "secondary");
            header('Location: /');
            exit();
        }

    }

    public static function viewAdmin(): bool
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    return isset($_SESSION['auth_user']) 
        && $_SESSION['auth_user']['role'] === 'admin';
}


    /**
     * Deconnecte l'utilisateur en supprimant la session
     * 
     */

    public static function offAuth() :void {

        unset($_SESSION['auth_user']);
    }

    }