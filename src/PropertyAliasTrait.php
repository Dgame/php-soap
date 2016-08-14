<?php

namespace Dgame\Soap;

/**
 * Trait PropertyAliasTrait
 * @package Dgame\Soap
 */
trait PropertyAliasTrait
{
    /**
     * @var array
     */
    private $aliase = [];

    /**
     * @param string $property
     * @param string $alias
     */
    final public function setPropertyAlias(string $property, string $alias)
    {
        $this->aliase[$property] = $alias;
    }

    /**
     * @param string $property
     *
     * @return bool
     */
    final public function hasPropertyAlias(string $property) : bool
    {
        return array_key_exists($property, $this->aliase);
    }

    /**
     * @param string $name
     *
     * @return string|null
     */
    final public function getPropertyAlias(string $name)
    {
        if ($this->hasPropertyAlias($name)) {
            return $this->aliase[$name];
        }

        return null;
    }
}