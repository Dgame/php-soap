<?php

namespace Dgame\Soap;

use Dgame\Soap\Attribute\Attribute;
use Dgame\Soap\Visitor\ElementVisitableInterface;
use Dgame\Soap\Visitor\ElementVisitorInterface;

/**
 * Class Element
 * @package Dgame\Soap
 */
class Element implements ElementVisitableInterface, AssignableInterface
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
     * @var Attribute[]
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
        $this->name = $name;

        if ($value !== null) {
            $this->setValue($value);
        }
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
        $this->value = trim($value);
    }

    /**
     * @return string
     */
    final public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param Attribute $attribute
     */
    public function setAttribute(Attribute $attribute)
    {
        $this->attributes[] = $attribute;
    }

    /**
     * @return bool
     */
    final public function hasAttributes(): bool
    {
        return !empty($this->attributes);
    }

    /**
     * @return Attribute[]
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