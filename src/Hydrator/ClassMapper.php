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
    public function appendClass(string $name, string $class)
    {
        $this->classmap[$name] = $class;
    }

    /**
     * @param string $pattern
     * @param string $class
     */
    public function appendPattern(string $pattern, string $class)
    {
        $this->pattern[$pattern] = $class;
    }

    /**
     * @param string $name
     *
     * @return Hydrate|null
     */
    public function new(string $name)
    {
        if (($class = $this->findClassName($name)) !== null) {
            return Hydrate::new($name, $class);
        }

        return null;
    }

    /**
     * @param string $class
     *
     * @return null|string
     */
    private function findClassName(string $class)
    {
        $name = $this->getClassName($class);
        if ($name !== null) {
            return $name;
        }

        foreach ($this->pattern as $pattern => $name) {
            if (preg_match($pattern, $class) === 1) {
                return $this->getClassName($name);
            }
        }

        return null;
    }

    /**
     * @param string $class
     *
     * @return null|string
     */
    private function getClassName(string $class)
    {
        foreach ($this->searchClassName($class) as $name) {
            if ($this->existsClass($name)) {
                return $name;
            }
        }

        return null;
    }

    /**
     * @param string $class
     *
     * @return \Generator
     */
    private function searchClassName(string $class)
    {
        foreach (Variants::ofArguments($class)->withCamelSnakeCase() as $name) {
            if ($this->hasClassInClassmap($name)) {
                yield $this->classmap[$name];
            }
        }
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