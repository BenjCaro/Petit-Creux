<?php

namespace Carbe\Petitcreuxv2\Models\Entites;

class User {
  
  private int $id;
  private string $name;
  private string $firstname;
  private string $email;
  private string $password;
  private string $role;
  private ?string $description;
  private string $createdAt; 

  public function __construct(array $data = []) {
        
       
      if (!empty($data)) {
        
            $this->id = $data['id'] ?? null;
            $this->name = $data['name'] ?? '';
            $this->firstname = $data['firstname'] ?? '';
            $this->email = $data['email'] ?? '';
            $this->password = $data['password'] ?? ''; 
            $this->role = $data['role'] ?? 'user';
            $this->description = $data['description'] ?? null;
            $this->createdAt = $data['created_at'] ?? date('Y-m-d');

      }

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

public function getFirstname() :string {
    return $this->firstname;
  }

public function setFirstname(string $firstname) :void {
    $this->firstname = $firstname;
  }


public function getEmail() :string {
     return $this->email;
  }

public function setEmail(string $email) :void {
    $this->email = $email;
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

public function setRole(string $role) :void {

    $this->role = $role;

  }

public function getDescription() :string {
     return $this->description;
  }

public function setDescription(?string $description) :void {
    $this->description = $description;
  }

public function getCreatedAt() :string {
    return date('Y-m-d', strtotime($this->createdAt));
  }

public function setCreatedAt(string $createdAt) :void {

    $this->createdAt = $createdAt;
  }


}