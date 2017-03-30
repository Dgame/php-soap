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
     * @return string
     */
    private function getClass(string $class): string
    {
        $class = $this->resolvePattern($class) ?? ucfirst($class);
        if (array_key_exists($class, $this->classmap)) {
            return $this->classmap[$class];
        }

        return $class;
    }

    /**
     * @param string $class
     *
     * @return string|null
     */
    private function resolvePattern(string $class): ?string
    {
        foreach ($this->pattern as $pattern => $name) {
            if (preg_match($pattern, $class) === 1) {
                return $name;
            }
        }

        return null;
    }

    /**
     * @param string $class
     *
     * @return HydratableInterface|null
     */
    public function getInstanceOf(string $class): ?HydratableInterface
    {
        $class = $this->getClass($class);

        return class_exists($class) ? new $class() : null;
    }
}