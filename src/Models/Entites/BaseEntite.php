<?php

namespace Carbe\Petitcreuxv2\Models\Entites;

class BaseEntite {
     

/**
 *  @param array<string, mixed> $data
 */

    public function hydrate(array $data): void {

        foreach ($data as $key => $value) {
            $camelCaseKey = lcfirst(str_replace('_', '', ucwords($key, '_')));
            $method = 'set' . ucfirst($camelCaseKey);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }
}