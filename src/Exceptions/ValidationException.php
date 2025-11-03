<?php

namespace Carbe\Petitcreuxv2\Exceptions;

use Exception;

class ValidationException extends Exception {

    private array $errors = [];

    public function __construct(array $errors) {
        
        $this->errors = $errors;
        
    }

    public function getErrors(): array {
        return $this->errors;
    }

    
}