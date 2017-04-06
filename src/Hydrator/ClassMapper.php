<?php

namespace Dgame\Soap\Hydrator;

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
     * @return string
     */
    private function lookUpClassName(string $class): string
    {
        return array_key_exists($class, $this->classmap) ? $this->classmap[$class] : $class;
    }

    /**
     * @param string $class
     *
     * @return string
     */
    private function getClassName(string $class)
    {
        $class = $this->lookUpClassName(ucfirst($class));

        return class_exists($class) ? $class : null;
    }

    /**
     * @param string $class
     *
     * @return HydratableInterface|null
     */
    public function getInstanceOf(string $class)
    {
        if (($class = $this->findClassName($class)) !== null) {
            return new $class();
        }

        return null;
    }
}