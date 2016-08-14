<?php

namespace Dgame\Soap;

/**
 * Class Attribute
 * @package Dgame\Soap
 */
final class Attribute
{
    /**
     * @var null|string
     */
    private $name = null;
    /**
     * @var null|string
     */
    private $value = null;

    use NamespaceTrait;

    /**
     * Attribute constructor.
     *
     * @param string $name
     * @param string $value
     */
    public function __construct(string $name, string $value)
    {
        $this->name  = $name;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getIdentifier() : string
    {
        if ($this->hasNamespace()) {
            return sprintf('%s:%s', $this->namespace, $this->name);
        }

        return $this->name;
    }

    /**
     * @return string
     */
    public function getValue() : string
    {
        return $this->value;
    }
}