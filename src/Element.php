<?php

namespace Dgame\Soap;

use Dgame\Soap\Attribute\Attribute;
use Dgame\Soap\Visitor\ElementVisitorInterface;

/**
 * Class Element
 * @package Dgame\Soap
 */
class Element
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

        $this->setValue($value ?? '');
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
        $value = trim($value);
        if (strlen($value) !== 0) {
            $this->value = $value;
        }
    }

    /**
     * @return bool
     */
    final public function hasValue(): bool
    {
        return $this->value !== null;
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
    final public function setAttribute(Attribute $attribute)
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