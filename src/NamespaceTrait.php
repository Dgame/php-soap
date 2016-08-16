<?php

namespace Dgame\Soap;

/**
 * Class NamespaceTrait
 * @package Dgame\Soap
 */
trait NamespaceTrait
{
    /**
     * @var string
     */
    private $namespace = '';

    /**
     * @param string|null $namespace
     */
    final public function setNamespace(string $namespace)
    {
        $this->onNamespaceChange(['old' => $this->namespace, 'new' => $namespace]);
        $this->namespace = $namespace;
    }

    /**
     * @param array $change
     */
    public function onNamespaceChange(array $change)
    {
    }

    /**
     * @return string
     */
    final public function getNamespace() : string
    {
        return $this->namespace;
    }

    /**
     * @return bool
     */
    final public function hasNamespace() : bool
    {
        return !empty($this->namespace);
    }

    /**
     * @param string $namespace
     *
     * @return bool
     */
    final public function hasEqualNamespace(string $namespace) : bool
    {
        return $this->namespace === $namespace;
    }
}