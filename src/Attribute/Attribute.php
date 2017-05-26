<?php

namespace Dgame\Soap\Attribute;

use Dgame\Soap\AssignableInterface;
use Dgame\Soap\Visitor\AttributeVisitableInterface;
use Dgame\Soap\Visitor\AttributeVisitorInterface;

/**
 * Class Attribute
 * @package Dgame\Soap\Attribute
 */
class Attribute implements AttributeVisitableInterface, AssignableInterface
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
     * @param string      $name
     * @param string|null $value
     */
    public function __construct(string $name, string $value = null)
    {
        $this->name  = $name;
        $this->value = $value;
    }

    /**
     * @return string
     */
    final public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    final public function hasValue(): bool
    {
        return !empty($this->value);
    }

    /**
     * @param string $value
     */
    final public function setValue(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    final public function getValue(): string
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