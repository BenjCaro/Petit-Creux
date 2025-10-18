<?php 

namespace Carbe\Petitcreuxv2\Models\Repository;

use Carbe\Petitcreuxv2\Models\Repository\BaseRepository;
use Carbe\Petitcreuxv2\Models\Entites\Post;
use Carbe\Petitcreuxv2\Models\Entites\User;
use Carbe\Petitcreuxv2\Models\Entites\Recipe;
use PDO;

class PostRepository extends BaseRepository {
   
   protected string $table = 'posts';
/**
 * @param array<string, mixed> $data
 */

public function __construct()
   {
      parent::__construct();

   }

/**
 * @return Post[]
 */

public function showApprovedComments(int $idRecipe) :array {

   $stmt = $this->pdo->prepare(
      "SELECT 
       posts.id,
       posts.title,
       posts.content,
       posts.createdAt,
       posts.id_user,
       users.name,
       users.firstname
      FROM posts JOIN users ON users.id = posts.id_user
      WHERE isApproved = 1
      AND posts.id_recipe = :id_recipe
      ORDER BY posts.createdAt DESC; ");
   $stmt->execute([
       'id_recipe' => $idRecipe
   ]);
   $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
   
   if(!$data) {
     
     return [];
   }

   $posts = [];
   foreach($data as $row) {
      $post = new Post($row);

      $user = new User([
            'id'        => $row['id'],
            'firstname' => $row['firstname'],
            'name'      => $row['name'],
        ]);

      $post->setAuthor($user);
      $posts[] = $post;
   }

   return $posts;

  
}

/**
 * @return PostModel|null
 */

public function getCommentById(int $id) {
    $stmt = $this->pdo->prepare(
        "SELECT 
            posts.id,
            posts.title AS post_title,
            posts.content,
            posts.createdAt,
            posts.isApproved,
            recipes.title AS recipe_title,
            recipes.createdAt AS recipe_createdAt,
            users.name AS user_name,
            users.firstname AS user_firstname
        FROM posts 
        JOIN recipes ON recipes.id = posts.id_recipe
        JOIN users ON users.id = posts.id_user
        WHERE posts.id = :id"
    );

    $stmt->execute([
        'id' => $id
    ]);

    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
        return null;
    }

   
    $post = new Post([
        'id'        => $data['id'],
        'title'     => $data['post_title'],
        'content'   => $data['content'],
        'createdAt' => $data['createdAt'],
        'isApproved'=> (bool) $data['isApproved']
    ]);

   
    $recipe = new Recipe([
        'title' => $data['recipe_title'],
        'createdAt' => $data['recipe_createdAt']
    ]);

    $user = new User([
       'name' => $data['user_name'],
       'firstname' => $data['user_firstname']
    ]);

    $post->setAuthor($user);
    $post->setRecipe($recipe);
    return $post;
}


/**
 * @return PostModel[]
 */



public function getCommentsByUser(int $id) :array {

   $stmt = $this->pdo->prepare("SELECT posts.title, posts.id, posts.isApproved, posts.createdAt, recipes.title as recipe_title, recipes.id as recipe_id, recipes.slug FROM posts 
   JOIN recipes ON posts.id_recipe = recipes.id
   WHERE posts.id_user = :id_user");
   $stmt->execute(['id_user' => $id]);
   $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

   if(!$data) {
      return [];
   }

   $posts = [];

   foreach($data as $row) {
      $post = new Post($row);
      $recipe = new Recipe([
         'id' => $row['recipe_id'],
         'title' => $row['recipe_title'],
         'slug' => $row['slug']
      ]);

      $post->setRecipe($recipe);
      $posts[] = $post;
   }

   return $posts;
}


/**
 * @return PostModel[]
 */

public function getLastestPost() :array {
    $stmt = $this->pdo->prepare("
    SELECT 
    posts.id,
    posts.title,
    posts.createdAt,
    posts.isApproved,
    users.name AS user_name,
    users.firstname AS user_firstname,
    recipes.title AS recipe_title,
    recipes.slug AS recipe_slug
    FROM posts
    JOIN users ON users.id = posts.id_user
    JOIN recipes ON recipes.id = posts.id_recipe
    ORDER BY createdAt DESC
    LIMIT 5
    ");

    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(!$data) {
      return [];
   }

   $posts = [];

   foreach($data as $row) {
      $post = new Post($row);

      $recipe = new Recipe([
            'title' => $row['recipe_title'],
            'slug' => $row['recipe_slug']
      ]);

      $user = new User([
         'name' => $row['user_name'],
         'firstname' => $row['user_firstname']
      ]);

      $post->setRecipe($recipe);
      $post->setAuthor($user);

     $posts[] = $post;
   }

   return $posts;
}

}