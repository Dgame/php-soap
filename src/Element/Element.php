<?php

namespace Dgame\Soap\Element;

use Dgame\Soap\Attribute\Attribute;
use Dgame\Soap\Visitor\ElementVisitableInterface;
use Dgame\Soap\Visitor\ElementVisitorInterface;

/**
 * Class Element
 * @package Dgame\Soap\Element
 */
class Element implements ElementVisitableInterface
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
     * @var array
     */
    private $attributes = [];

    /**
     * Element constructor.
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
        return $this->value ?? '';
    }

    /**
     * @return bool
     */
    final public function hasValue(): bool
    {
        return !empty($this->value);
    }

    /**
     * @param Attribute $attribute
     */
    final public function attachAttribute(Attribute $attribute)
    {
        $attribute->attachedBy($this);

        $this->attributes[] = $attribute;
    }

    /**
     * @return array
     */
    final public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param ElementVisitorInterface $visitor
     */
    public function accept(ElementVisitorInterface $visitor)
    {
        $visitor->visitElement($this);
    }
}