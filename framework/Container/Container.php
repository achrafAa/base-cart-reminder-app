<?php

namespace Achraf\framework\Container;

use ReflectionException;

class Container
{
    private array $container = [];

    public function get(string $className): ?object
    {
        return $this->container[$className] ?? $this->buildObject($className);
    }

    public function bind(string $className, callable $callable): void
    {
        $this->container[$className] = call_user_func($callable);
    }

    /**
     * @throws ReflectionException
     */
    private function buildObject(string $className): object
    {
        $args = [];
        $reflection = new \ReflectionClass($className);
        $parameters = $reflection->getConstructor()?->getParameters() ?? [];
        foreach ($parameters as $parameter) {
            $typeName = $parameter->getType()->getName();
            $args[] = $this->container[$typeName] ?? new ($typeName);
        }

        return $reflection->newInstanceArgs($args);
    }
}
