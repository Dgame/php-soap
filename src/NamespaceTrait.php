<?php

namespace Dgame\Soap;

/**
 * Class NamespaceTrait
 * @package Dgame\Soap
 */
trait NamespaceTrait
{
    /**
     * @var null|string
     */
    private $namespace = null;

    /**
     * @param string $namespace
     */
    final public function setNamespace(string $namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @return bool
     */
    final public function hasNamespace() : bool
    {
        return !empty($this->namespace);
    }

    /**
     * @return null|string
     */
    final public function getNamespace()
    {
        return $this->namespace;
    }
}