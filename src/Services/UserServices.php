<?php

namespace Carbe\Petitcreuxv2\Services;

use Carbe\Petitcreuxv2\Models\Repository\UserRepository;
use Carbe\Petitcreuxv2\Models\Repository\RecipeRepository;
use Carbe\Petitcreuxv2\Models\Repository\PostRepository;
use Exception;
use Carbe\Petitcreuxv2\Helpers\Csrf;
use Carbe\Petitcreuxv2\Models\Entites\User;

class UserServices {

    private UserRepository $userRepo;
    private RecipeRepository $recipeRepo;
    private PostRepository $postRepo;
   
    public function __construct(UserRepository $userRepo, RecipeRepository $recipeRepo, PostRepository $postRepo) {
        $this->userRepo = $userRepo;
        $this->recipeRepo = $recipeRepo;
        $this->postRepo = $postRepo;
        
    }
    

    // public function registerUser(array $data) :?User {



    //  }

    private function availableEmail(string $email, ?int $currentUserId = null): bool {

            $user = $this->userRepo->findUserWithEmail($email);

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
}