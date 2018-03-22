<?php

namespace Dgame\Soap\Hydrator;

use Dgame\Variants\Variants;

/**
 * Class ClassMapper
 * @package Dgame\Soap\Hydrator
 */
final class ClassMapper
{
    /**
     * @var array
     */
    private $classmap = [];
    /**
     * @var array
     */
    private $pattern = [];

    /**
     * AutoLoad constructor.
     *
     * @param array $classmap
     */
    public function __construct(array $classmap = [])
    {
        $this->classmap = $classmap;
    }

    /**
     * @param string $name
     * @param string $class
     */
    public function appendClass(string $name, string $class): void
    {
        $this->classmap[$name] = $class;
    }

    /**
     * @param string $pattern
     * @param string $class
     */
    public function appendPattern(string $pattern, string $class): void
    {
        $this->pattern[$pattern] = $class;
    }

    /**
     * @param string $name
     *
     * @return object|null
     */
    public function new(string $name)
    {
        foreach (Variants::ofArguments($name)->withCamelSnakeCase() as $class) {
            $class = $this->findClass($class);
            if ($class !== null) {
                return new $class();
            }
        }

        return null;
    }

    /**
     * @param string $class
     *
     * @return null|string
     */
    private function findClass(string $class)
    {
        $class = $this->resolveClass($class);
        if ($this->existsClass($class)) {
            return $class;
        }

        return $this->searchForClassPattern($class);
    }

    /**
     * @param string $class
     *
     * @return null|string
     */
    private function searchForClassPattern(string $class)
    {
        foreach ($this->pattern as $pattern => $name) {
            if (preg_match($pattern, $class) === 1) {
                $class = $this->resolveClass($name);
                if ($this->existsClass($class)) {
                    return $class;
                }
            }
        }

        return null;
    }

    /**
     * @param string $class
     *
     * @return string
     */
    private function resolveClass(string $class): string
    {
        return $this->hasClassInClassmap($class) ? $this->classmap[$class] : $class;
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    private function hasClassInClassmap(string $class): bool
    {
        return array_key_exists($class, $this->classmap);
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    private function existsClass(string $class): bool
    {
        return class_exists($class);
    }
}