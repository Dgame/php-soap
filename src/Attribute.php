<?php

namespace Dgame\Soap;

use Dgame\Soap\Visitor\AttributeVisitor;

/**
 * Class Attribute
 * @package Dgame\Soap
 */
class Attribute
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $value;

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
     * @return bool
     */
    public function isUsed(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    final public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    final public function getValue() : string
    {
        return $this->value;
    }

    /**
     * @param AttributeVisitor $visitor
     */
    public function accept(AttributeVisitor $visitor)
    {
        $visitor->visitAttribute($this);
    }
}