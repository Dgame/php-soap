<?php

namespace Dgame\Soap\Element;

use Dgame\Soap\Attribute\AttributeInterface;
use Dgame\Soap\NameTrait;
use Dgame\Soap\ValueTrait;
use Dgame\Soap\Visitor\ElementVisitorInterface;

/**
 * Class Element
 * @package Soap\Element
 */
class Element implements ElementInterface
{
    use NameTrait, ValueTrait;

    /**
     * @var AttributeInterface[]
     */
    private $attributes = [];

    /**
     * Element constructor.
     *
     * @param string $name
     * @param null   $value
     */
    public function __construct(string $name, $value = null)
    {
        $this->name = trim($name);
        $this->setValue($value);
    }

    /**
     * @return bool
     */
    final public function hasAttributes(): bool
    {
        return !empty($this->attributes);
    }

    /**
     * @return AttributeInterface[]
     */
    final public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param AttributeInterface $attribute
     */
    final public function setAttribute(AttributeInterface $attribute): void
    {
        $this->attributes[] = $attribute;
    }

    /**
     * @param ElementVisitorInterface $visitor
     */
    public function accept(ElementVisitorInterface $visitor): void
    {
        $visitor->visitElement($this);
    }
}