<?php

namespace Dgame\Soap\Attribute;

use Dgame\Soap\NameTrait;
use Dgame\Soap\ValueTrait;
use Dgame\Soap\Visitor\AttributeVisitorInterface;

/**
 * Class Attribute
 * @package Soap\Attribute
 */
class Attribute implements AttributeInterface
{
    use NameTrait, ValueTrait;

    /**
     * Attribute constructor.
     *
     * @param string $name
     * @param mixed  $value
     */
    public function __construct(string $name, $value = null)
    {
        $this->name = trim($name);
        $this->setValue($value);
    }

    /**
     * @param AttributeVisitorInterface $visitor
     */
    public function accept(AttributeVisitorInterface $visitor): void
    {
        $visitor->visitAttribute($this);
    }
}
