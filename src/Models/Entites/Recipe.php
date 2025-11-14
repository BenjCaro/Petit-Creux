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
    private string $state;
    private Category $category;
    private User $user;
    private Description $description;

    public function __construct(array $data = []) {
        
       
      if (!empty($data)) {
        
          $this->setId($data['id']);
          $this->setTitle($data['title'] ?? '');
          $this->setSlug($data['slug'] ?? '');
          $this->setIdUser($data['idUser'] ?? '');
          $this->setIdCategory($data['idCategory'] ?? '');
          $this->setCreatedAt($data['createdAt'] ?? date('Y-m-d'));
          $this->setDuration($data['duration'] ?? null);
          $this->setState($data['state'] ??'');
          $this->setCategory($data["category"] ?? '');
          $this->setDescription($data['description'] ?? '');

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

public function getDescription() : Description{
    return $this->description;
}

/** @param Description $description */
public function setDescription(Description $description) : void {
    $this->description = $description;
} 

}