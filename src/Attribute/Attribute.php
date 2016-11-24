<?php

namespace Dgame\Soap\Attribute;

use Dgame\Soap\Element\Element;
use Dgame\Soap\Visitor\AttributeVisitableInterface;
use Dgame\Soap\Visitor\AttributeVisitorInterface;

/**
 * Class Attribute
 * @package Dgame\Soap
 */
class Attribute implements AttributeVisitableInterface
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
     * @var int
     */
    private $usage = 0;

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
    final public function getName(): string
    {
        return $this->name;
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
     * @return int
     */
    final public function getUsage(): int
    {
        return $this->usage;
    }

    /**
     *
     */
    final public function increaseUsage()
    {
        $this->usage++;
    }

    /**
     * @return bool
     */
    public function isUsed(): bool
    {
        return true;
    }

    /**
     * @param AttributeVisitorInterface $visitor
     */
    public function accept(AttributeVisitorInterface $visitor)
    {
        $visitor->visitAttribute($this);
    }

    public function attachedBy(Element $element)
    {
    }
}