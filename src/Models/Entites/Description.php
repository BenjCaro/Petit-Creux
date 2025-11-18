<?php 

namespace Carbe\Petitcreuxv2\Models\Entites;

class Description {
     
    private ?int $id;
    private int $step_number;
    private string $texte;
    private int $id_recipe;
 

  public function __construct(array $data = [])
  {
    if(!empty($data)) {
        $this->setId($data['id']);
        $this->setStepNumber($data['step_number'] ?? 0);
        $this->setTexte($data['texte'] ?? '');
        $this->setIdRecipe($data['id_recipe'] ?? 0);
    }
}

public function getId() : ?int {
    return $this->id;
}

public function getStepNumber() :int {
    return $this->step_number;
}

public function getTexte() :string {
    return $this->texte;
}

public function getIdRecipe() :int {
    return $this->id_recipe;
}

public function setId(?int $id) :void {
    $this->id = $id;
}

public function setStepNumber(int $step_number) :void {
    $this->step_number = $step_number;
}

public function setTexte(string $texte) :void {
    $this->texte = $texte;
}

public function setIdRecipe(int $id_recipe) :void {
    $this->id_recipe = $id_recipe;
}
 }