<?php 

namespace Carbe\Petitcreuxv2\Models\Entites;

class Category {

    private int $id;
    private string $name;
    private string $slug;
    private ?string $image;
    private int $totalRecipes = 0;

  /**
 * @param array<string, mixed> $data
 */

    public function __construct(array $data = []) {
        
      if (!empty($data)) {
            
        $this->name = $_data["name"] ?? '';
        $this->slug = $_data["slug"] ?? '';
        $this->image = $_data["image"] ?? '';

      }

    }

    public function getId() : ?int {
        return $this->id;
    }

    public function setId(int $id) :void {
        $this->id = $id;
    }

  public function getName() :string {
        return $this->name;
  }
  
  public function setName(string $name) :void  {
       $this->name = $name;
  }

  public function getSlug() : string {
     return $this->slug;
  }

  public function getTotalRecipes() :int {
    return $this->totalRecipes;
  }

  public function setTotalRecipes(int $totalRecipes):void {
    $this->totalRecipes = $totalRecipes;
  }

  public function setSlug(string $slug) : void {
     $this->slug = $slug;
  }

  public function getImage() :?string {
    return $this->image;
  }

  public function setImage(?string $image) :void {
    $this->image = $image;
  }
}