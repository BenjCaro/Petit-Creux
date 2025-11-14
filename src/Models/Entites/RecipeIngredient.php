<?php

namespace Carbe\Petitcreuxv2\Models\Entites;
use Carbe\Petitcreuxv2\Models\Entites\Recipe;

class RecipeIngredient {
    
    private int $id;
    private int $quantity;
    private string $unit;
    private Ingredient $ingredient;

   public function __construct(array $data = [])  {

    if(!empty($data)) {

        
        $this->setId($data['id'] ?? '');
        $this->setQuantity($data['quantity'] ?? '');
        $this->setUnit($data['unit'] ?? '');
        $this->setIngredient($data['ingredient'] ?? '');

    }
   }

   public function getId() : ?int {
        return $this->id;
    }

  public function setId(int $id) :void {
        $this->id = $id;
    }

    public function setIngredient(Ingredient $ingredient): void {
    $this->ingredient = $ingredient;
    
    }


    public function getIngredient(): Ingredient {
        return $this->ingredient;
    }

    public function getQuantity() :int {
        return $this->quantity;
    }

    public function setQuantity(int $quantity) :void {
            $this->quantity = $quantity;
    }

    public function getUnit() :string {
        return $this->unit;
    }

    public function setUnit(?string $unit) :void {
        $this->unit = $unit ?? '';
    }



}