<?php
namespace Carbe\Petitcreuxv2\Core;
use ReflectionClass;

class Container
{
    private array $instances = [];

    public function get(string $class)
    {
        
        if (isset($this->instances[$class])) {
            return $this->instances[$class];
        }

        $reflection = new ReflectionClass($class);
        $constructor = $reflection->getConstructor();

        if (!$constructor) {
            $instance = new $class();
        } else {
            $params = [];
            foreach ($constructor->getParameters() as $param) {
                $paramClass = $param->getType()?->getName();
                $params[] = $paramClass ? $this->get($paramClass) : null;
            }
            $instance = $reflection->newInstanceArgs($params);
        }

        $this->instances[$class] = $instance;
        return $instance;
    }
}
