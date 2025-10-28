<?php

namespace Carbe\Petitcreuxv2\Services;

use Carbe\Petitcreuxv2\Models\Repository\UserRepository;
use Carbe\Petitcreuxv2\Models\Repository\RecipeRepository;
use Carbe\Petitcreuxv2\Models\Repository\PostRepository;
use Exception;
use Carbe\Petitcreuxv2\Helpers\Csrf;
use Carbe\Petitcreuxv2\Models\Entites\User;
use Carbe\Petitcreuxv2\Exceptions\ValidationException;
use PHPUnit\TextUI\XmlConfiguration\ValidationResult;

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

        $username = trim($data['username'] ?? '');
        $name = trim($data['name'] ?? '');
        $firstname = trim($data['firstname'] ?? '');
        $email = filter_var(trim($data['email'] ?? ''), FILTER_VALIDATE_EMAIL);
        $password = trim($data['password'] ?? '');
        $confirm = trim($data['confirm-password'] ?? '');
        $description = trim($data['description'] ?? '');

        if(!$username) {
            throw new ValidationException("Champs Pseudo Obligatoire!");
        } elseif(!$this->availableUsername($username)) {
            throw new ValidationException("Pseudo déja existant");
        }

        if (!$email) { 
            throw new ValidationException("Adresse Email invalide!");
        
        } elseif (!$this->availableEmail($email)) {
            throw new ValidationException("L'Adresse e-mail déjà utilisée.");
        }

        if (strlen($password) < 8) {
            throw new ValidationException("Le mot de passe doit contenir au moins 8 caractères.");
        }

        if ($password !== $confirm) {
            throw new ValidationException("Les mots de passe ne correspondent pas.");
        }

        if (empty($name)) {
            throw new ValidationException("Champs Nom Obligatoire");
        }

        if (empty($firstname)) {
            throw new ValidationException("Champs Prénom Obligatoire");
        }

        if (!empty($description)) {
            // Supprimer les balises HTML (sécurité)
            $description = strip_tags($description);
        }

        if (strlen($description) > 500) {
            throw new ValidationException("La description ne peut pas dépasser 500 caractères.");
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

   