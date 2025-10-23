<?php 
namespace Carbe\Petitcreuxv2\Models\Entites;

class Ingredient {

    private int $id;
    private string $name;
    private string $type;

  public function __construct(array $data = [])
  {

    $this->id = $data['id'] ?? '';
    $this->name = $data['name'] ?? '';
    $this->type = $data["type"] ?? '';
    
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

  public function getType() :string {
        return $this->type;
  }
  
  public function setType(string $type) :void  {
       $this->type = $type;
  }

}