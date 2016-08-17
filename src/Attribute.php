<?php

namespace Dgame\Soap;

use Dgame\Soap\Visitor\AttributeVisitorInterface;

/**
 * Class Attribute
 * @package Dgame\Soap
 */
class Attribute implements AttributeInterface
{
    /**
     * @var null|string
     */
    private $name  = null;
    /**
     * @var null|string
     */
    private $value = null;

    /**
     * Attribute constructor.
     *
     * @param string $name
     * @param string $value
     */
    public function __construct(string $name, string $value)
    {
        $this->name = $name;
        $this->value = $value;
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
     * @param AttributeVisitorInterface $visitor
     */
    public function accept(AttributeVisitorInterface $visitor)
    {
        $visitor->visitAttribute($this);
    }
}