<?php

namespace Achraf\framework\Container;

use ReflectionException;

class Container
{
    /**
     * @var array
     */
    private array $container = [];

    /**
     * @param  string  $className
     * @return object|null
     */
    public function get(string $className): ?object
    {
        return $this->container[$className] ?? $this->buildObject($className);
    }

    /**
     * @param  string  $className
     * @param  callable  $callable
     * @return void
     */
    public function bind(string $className, callable $callable): void
    {
        $this->container[$className] = call_user_func($callable);
    }

    /**
     * @param  string  $className
     * @return object
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
