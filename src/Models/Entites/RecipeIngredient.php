<?php

namespace Carbe\App\Models\Entities;
use Carbe\App\Models\Entities\Recipe;

class RecipeIngredient {
    
    private int $id;
    private int $quantity;
    private string $unit;
    private Ingredient $ingredient;

   public function __construct(array $data = [])  {

    if(!empty($data)) {

        $this->id = $data["id"] ?? '';
        $this->quantity = $data["quantity"] ?? '';
        $this->unit = $data["unit"] ?? ''; 
        $this->ingredient = $data["ingredient"] ?? '';
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