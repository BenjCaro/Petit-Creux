<?php

namespace Carbe\Petitcreuxv2\Services;

use Carbe\Petitcreuxv2\Models\Repository\UserRepository;
use Carbe\Petitcreuxv2\Models\Repository\RecipeRepository;
use Carbe\Petitcreuxv2\Models\Repository\PostRepository;
use Exception;
use Carbe\Petitcreuxv2\Helpers\Csrf;
use Carbe\Petitcreuxv2\Models\Entites\User;
use Carbe\Petitcreuxv2\Exceptions\ValidationException;


class UserServices {

    private UserRepository $userRepo;
    //private RecipeRepository $recipeRepo;
    // private PostRepository $postRepo;
   
    /**
     * 
     * ajouter ds le contruct RecipeRepo et PostRepo
     */
    
    public function __construct(UserRepository $userRepo) {
        $this->userRepo = $userRepo;
      // $this->recipeRepo = $recipeRepo;
      // $this->postRepo = $postRepo;
        
    }
    

    public function registerUser(array $data) :?User {

        // $token = $data['token'];
        // Csrf::check("register_form", $token, "/register");
        
        $username = $data['username'] ?? '';
        $name = $data['name'] ?? '';
        $firstname = $data['firstname'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $confirm = $data['confirm-password'] ?? '';
        $description = $data['description'] ??'';


        $errors = [];

        if(!$username) {
            $errors['username'] = "Champs Pseudo Obligatoire!";
        } elseif(!$this->availableUsername($username)) {
             $errors['username'] = "Pseudo déja existant";
        }

        if (!$email) { 
             $errors['email'] = "Champs Email Obligatoire";
        
        } elseif (!$this->availableEmail($email)) {
             $errors['email'] = "L'Adresse e-mail déjà utilisée.";
        }

        if (strlen($password) < 8) {
            $errors['password_length'] = "Le mot de passe doit contenir au moins 8 caractères.";
        
        }

        if ($password !== $confirm) {
            $errors['password_match'] ="Les mots de passe ne correspondent pas.";
        }

        if (empty($name)) {
            $errors['name'] ="Champs Nom Obligatoire";
        }

        if (empty($firstname)) {

          $errors['firstname'] = "Champs Prénom Obligatoire";
        }

        if (strlen($description) > 500) {
            $errors['description'] = "La description ne peut pas dépasser 500 caractères.";
        }

        if(!empty($errors)) {
            throw new ValidationException($errors);
        }

        // Insertion en base 
        
          $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
          $userData = [
            'username' => $username,
            'name' => $name,
            'firstname' => $firstname,
            'email' => $email,
            'password' => $hashedPassword,
            'description' => $description
           ];

         $user = new User($userData);
         $created = $this->userRepo->createUser($user);

        return $created ? $user : null;


    }

    /**
     * Gestion de la connexion utilisateur
     * 
     */

    public function logIn(string $token, string $email, string $password) :void {

        Csrf::check("login", $token, "/login");


        $errors = [];

        if(empty($email) || empty($password)) {
            $errors["log"] = "Champs Vides!";
    
        }

        $auth_user = $this->userRepo->findUser("email", $email);
        if(!$auth_user) {
            $errors['email'] = "L'adresse email ne correspond à aucun utilisateur";   

        }

        elseif(!password_verify($password, $auth_user->getPassword())) {
           
            $errors["password"] = "Mot de passe invalide";
        }

        if (!empty($errors)) {
            throw new ValidationException($errors);
        }

        $_SESSION['auth_user'] = [
                'id' => $auth_user->getId(),
                'role' => $auth_user->getRole()
        ];
    }

    private function availableEmail(string $email, ?int $currentUserId = null): bool {
        $user = $this->userRepo->findUser("email", $email);

        // Si aucun utilisateur trouvé → email dispo
        if (!$user) {
            return true;
        }

        // Si c'est l'utilisateur actuel → email considéré comme dispo
        if ($currentUserId !== null && $user->getId() === $currentUserId) {
            return true;
        }

        // Sinon email déjà pris par un autre utilisateur
        return false;
    }

    private function availableUsername(string $username, ?int $currentUserId = null) :bool {
         
        $user = $this->userRepo->findUser("username", $username);

         if (!$user) {
            return true;
        }

        // Si c'est l'utilisateur actuel → email considéré comme dispo
        if ($currentUserId !== null && $user->getId() === $currentUserId) {
            return true;
        }

        // Sinon email déjà pris par un autre utilisateur
        return false;
    }



}

   