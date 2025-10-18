<?php

namespace Carbe\Petitcreuxv2\Models\Entites;


class Favoris {
    
    private int $id;
    private int $idUser; 
    private int $idRecipe;

    public function __construct(array $data = [])
    {
        if(!empty($data)) {
            $this->id = $data["id"] ?? '';
            $this->idUser = $data["idUser"] ?? '';
            $this->idRecipe = $data["idRecipe"] ?? '';
        }
    }

    public function getId() : ?int {
        return $this->id;
    }

   public function setId(int $id) :void {
        $this->id = $id;
    }

    public function getIdUser(): int {
    return $this->idUser;
}

public function setIdUser(int $idUser): void {
    $this->idUser = $idUser;
}

public function getIdRecipe(): int {
    return $this->idRecipe;
}

public function setIdRecipe(int $idRecipe): void {
    $this->idRecipe = $idRecipe;
}


}