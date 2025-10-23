<?php

namespace Carbe\Petitcreuxv2\Models\Entites;

use Carbe\Petitcreuxv2\Models\Entites\User;
use Carbe\Petitcreuxv2\Models\Entites\Category;

class Recipe {

    private array $ingredients = [];
    private int $id;
    private string $title;
    private string $slug;
    private int $idUser;
    private int $idCategory;
    private string $createdAt;
    private int $duration;
    private ?string $description;
    private string $state;
    private Category $category;
    private User $user;
     

    public function __construct(array $data = []) {
        
       
      if (!empty($data)) {
        
          $this->id = $data['id'] ?? null;
          $this->title = $data['title'] ?? '';
          $this->slug = $data['slug'] ?? '';
          $this->idUser = $data['idUser'] ?? '';
          $this->idCategory = $data['idCategory'] ?? ''; 
          $this->createdAt = $data['createdAt'] ?? date('Y-m-d');
          $this->duration = $data['duration'] ?? null;
          $this->state = $data['state'] ?? date('Y-m-d');
          $this->category = $data["category"] ?? '';

      } }

    public function getId() : ?int {
        return $this->id;
    }

    public function setId(int $id) :void {
        $this->id = $id;
    }


    public function getTitle() :string {
        return $this->title;
   }

   public function setTitle(string $title) :void {
    $this->title = $title;
   }

   public function getSlug() : string {
     return $this->slug;
  }

  public function setSlug(string $slug) : void {
     $this->slug = $slug;
  }

  public function getIdUser(): int {
    return $this->idUser;
}

public function setIdUser(int $idUser): void {
    $this->idUser = $idUser;
}

public function getIdCategory(): int {
    return $this->idCategory;
}

public function setIdCategory(int $idCategory): void {
    $this->idCategory = $idCategory;
}


public function getCreatedAt() :string {
    return $this->createdAt;
  }

public function setCreatedAt(string $createdAt) : void {
   $this->createdAt = $createdAt;
  }

public function getDuration() :int {
     return $this->duration;

  }

public function setDuration(int $duration) :void {
     $this->duration = $duration;
  }

public function getDescription() :?string {
     return $this->description;
  }

public function setDescription(?string $description) :void {
    $this->description = $description;
  }

public function getState() :string {
    return $this->state;
}

public function setState(string $state) :void {
    $this->state = $state;
}

public function getCategory(): Category {
    return $this->category;
}

public function setCategory(Category $category):void {
  $this->category = $category;
}

public function getIngredients(): array {
      return $this->ingredients;
}  
/** @param RecipeIngredientModel[] $ingredients */
public function setIngredients(array $ingredients) :void {

    $this->ingredients = $ingredients;
    
 }

public function getUser() :User {
    return $this->user;
}

public function setUser(User $user) :void{
    $this->user = $user;
}

}