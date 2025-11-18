<?php 

namespace Carbe\Petitcreuxv2\Models\Repository;
use Carbe\Petitcreuxv2\Models\Entites\Description;

class DescriptionRepository extends BaseRepository {

    protected string $table = "descriptions";
    
    public function __construct()
    {
        parent::__construct();
    }

    public function createDescriptionRecipe(Description $description) :bool{
       $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (step_number, texte, id_recipe)
       VALUES (:step_number, :texte, :id_recipe)");
       return  $stmt->execute([
            'step_number' => $description->getStepNumber(),
            'texte' => $description->getTexte(),
            'id_recipe' => $description->getIdRecipe()
       ]);
    } 
    

}