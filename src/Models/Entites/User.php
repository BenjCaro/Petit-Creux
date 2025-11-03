<?php

namespace Carbe\Petitcreuxv2\Models\Entites;

use Carbe\Petitcreuxv2\Exceptions\ValidationException;

class User {
  
  private ?int $id;
  private string $username;
  private string $name;
  private string $firstname;
  private string $email;
  private string $password;
  private string $role;
  private string $description = "J'aime la communauté Petit Creux!";
  private string $createdAt; 

  
      
  public function __construct(array $data = []) {
        if (!empty($data)) {
            $this->setId($data['id']);
            $this->setUsername($data['username'] ?? '');
            $this->setName($data['name'] ?? '');
            $this->setFirstname($data['firstname'] ?? '');
            $this->setEmail($data['email'] ?? '');
            $this->setPassword($data['password'] ?? '');
            $this->setRole($data['role'] ?? 'user');
            $this->setDescription($data['description'] ?? null);
            $this->setCreatedAt($data['created_at'] ?? date('Y-m-d'));
        }
    }
    
  
  public function getId() : ?int {
      return $this->id;
    }

  public function setId(?int $id) :void {
        $this->id = $id;
    }
  
  public function getUsername() :string {
      return $this->username;
    }

  public function setUsername(string $username) :void {
     $this->username = trim($username);
  }

  public function getName() :string {
    return $this->name;
  }
  
  public function setName(string $name) :void  {
     $this->name = trim($name);
  }

public function getFirstname() :string {
    return $this->firstname;
  }

public function setFirstname(string $firstname) :void {
    $this->firstname = trim($firstname);
  }


public function getEmail() :string {
     return $this->email;
  }

public function setEmail(string $email) :void {
    
    if(!filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
       throw new ValidationException(['email' => "L'email est invalide"]);

    } else {
      $this->email = filter_var(trim($email), FILTER_VALIDATE_EMAIL);
    }
    
  }

public function getPassword() :string {
   return $this->password;
  }

public function setPassword(string $password) :void {
    $this->password = password_hash($password, PASSWORD_DEFAULT); 
  }

public function getRole() :string {
    return $this->role;
  }

public function setRole(string $role): void {
    $allowedRoles = ['user', 'admin'];
    $this->role = in_array($role, $allowedRoles) ? $role : 'user';
}


public function getDescription() :string {
     return $this->description;
  }

public function setDescription(?string $description): void {
    // nettoyer la description
    $clean = $description ? strip_tags(trim($description)) : '';
    
    // appliquer la valeur par défaut seulement si vide
    $this->description = $clean !== '' ? $clean : "J'aime la communauté Petit Creux!";
}

public function getCreatedAt() :string {
    return date('Y-m-d', strtotime($this->createdAt));
  }

public function setCreatedAt(string $createdAt) :void {

    $this->createdAt = $createdAt;
  }


}