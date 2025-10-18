<?php 

namespace Carbe\Petitcreuxv2\Models\Entites;

use Carbe\Petitcreuxv2\Models\Entites\User;
use Carbe\Petitcreuxv2\Models\Entites\Recipe;

class Post {
      
   private string $title;
   private string $content;
   private int $id_user;
   private int $id_recipe;
   private string $createdAt;
   private bool $isApproved = false;
   private ?User $author = null;
   private ?Recipe $recipe= null;


    public function __construct(array $data = []) {

        if (!empty($data)) {
                    
                $this->title = $data["title"] ?? '';
                $this->content = $data["content"] ?? '';
                $this->id_user = $data["id_user"] ?? '';
                $this->id_recipe = $data["id_recipe"] ?? '';
                $this->createdAt = $data["createdAt"] ?? '';
                $this->isApproved = $data["isApproved"];
                $this->author = $data["author"] ?? '';
                // $this->recipe;

        } 

    }


   public function getTitle() :string {
        return $this->title;
    }

  public function setTitle(string $title) :void {
    $this->title = $title;
   }
   
  public function getContent() :string {
        return $this->content;
    }

 public function setContent(string $content) :void {
        $this->content = $content;
    }

 public function getIdUser() :int {
    return $this->id_user;
    }

 public function setIdUser(int $id_user): void {
    $this->id_user = $id_user;
    }

 public function getIdRecipe() :int {
   return $this->id_recipe;
}

 public function setIdRecipe(int $id_recipe) :void {
   $this->id_recipe = $id_recipe;
}

 public function getCreatedAt() :string {
    return $this->createdAt;
  }

 public function setCreatedAt(string $createdAt) :void {
    $this->createdAt = $createdAt;
  }

 public function getIsApproved() :bool {
    return $this->isApproved;
  }

 public function setIsApproved(bool $isApproved) :void {
    $this->isApproved = $isApproved;
    
  }

 public function approve(): void {
    $this->isApproved = true;
}


public function setAuthor(User $user): void {
        $this->author = $user;
}

public function getAuthor(): ?User {
        return $this->author;
 }

public function getRecipe(): ?Recipe {
   return $this->recipe;
}

public function setRecipe(?Recipe $recipe) :void {
    $this->recipe = $recipe;
}


}