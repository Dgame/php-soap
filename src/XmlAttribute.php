<?php

namespace Dgame\Soap;

/**
 * Class PrefixedAttribute
 * @package Dgame\Soap
 */
abstract class XmlAttribute implements AttributeInterface
{
    /**
     * @var null|string
     */
    private $value = null;
    /**
     * @var null|string
     */
    private $prefix = null;
    /**
     * @var null|string
     */
    private $namespace = null;

    /**
     * PrefixedAttribute constructor.
     *
     * @param string      $prefix
     * @param string      $value
     * @param string|null $namespace
     */
    public function __construct(string $prefix, string $value, string $namespace = null)
    {
        $this->prefix    = $prefix;
        $this->value     = $value;
        $this->namespace = $namespace;
    }

    /**
     * @return bool
     */
    public function hasPrefix() : bool
    {
        return !empty($this->prefix);
    }

    /**
     * @return null|string
     */
    final public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @return string
     */
    final public function getValue() : string
    {
        return $this->value;
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

    /**
     * @return string
     */
    final public function getName() : string
    {
        if ($this->hasNamespace() && $this->hasPrefix()) {
            return sprintf('%s:%s', $this->namespace, $this->prefix);
        }

        return $this->hasPrefix() ? $this->prefix : $this->namespace;
    }
}