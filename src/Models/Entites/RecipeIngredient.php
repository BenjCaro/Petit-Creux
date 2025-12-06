<?php

namespace Carbe\Petitcreuxv2\Models\Entites;
use Carbe\Petitcreuxv2\Models\Entites\Recipe;

class RecipeIngredient  extends BaseEntite
{
   
    private int $id_recipe;
    private int $id_ingredient;
    private int $quantity;
    private string $unit;

    public function __construct(array $data = []) 
    {
        if (!empty($data)) {
            
            $this->setIdRecipe((int)$data['id_recipe'] ?? 0);
            $this->setIdIngredient((int)$data['id_ingredient'] ?? 0);
            $this->setQuantity($data['quantity'] ?? 0);
            $this->setUnit($data['unit'] ?? '');
        }
    }

   
    public function getIdRecipe(): int 
    {
        return $this->id_recipe;
    }

    public function setIdRecipe(int $idRecipe): void 
    {
        $this->id_recipe = $idRecipe;
    }


    public function getIdIngredient(): int 
    {
        return $this->id_ingredient;
    }

    public function setIdIngredient(int $idIngredient): void 
    {
        $this->id_ingredient = $idIngredient;
    }

    
    public function getQuantity(): int 
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void 
    {
        $this->quantity = $quantity;
    }

   
    public function getUnit(): string 
    {
        return $this->unit;
    }

    public function setUnit(string $unit): void 
    {
        $this->unit = $unit;
    }
}
