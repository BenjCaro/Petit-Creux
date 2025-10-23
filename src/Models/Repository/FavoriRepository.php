<?php 

namespace Carbe\Petitcreuxv2\Models\Repository;
use Carbe\Petitcreuxv2\Models\Repository\BaseRepository;
use PDO;

class FavorisRepository extends BaseRepository {

    protected string $table = "favoris";

/**
 * @param array<string, mixed> $data
 */
public function __construct()
    {      
        parent::__construct();
        
    }

public function ifFavorisExist(int $idUser, int $idRecipe) :bool {
     $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM favoris where favoris.id_user = :id_user and favoris.id_recipe = :id_recipe");
     $stmt->execute([
        'id_user' => $idUser,
        'id_recipe' => $idRecipe
    ]);
    return $stmt->fetchColumn() > 0;  // retourne true si > 0 donc favoris deja existant

}

public function removeFavoris(int $idUser, int $idRecipe): void {
    $stmt = $this->pdo->prepare('DELETE FROM favoris WHERE id_user = :id_user AND id_recipe = :id_recipe');
    $stmt->execute(['id_user' => $idUser,
        'id_recipe' => $idRecipe]);
}

}
