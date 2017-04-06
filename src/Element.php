<?php

namespace Dgame\Soap;

use Dgame\Soap\Attribute\Attribute;
use Dgame\Soap\Hydrator\VisitableInterface;
use Dgame\Soap\Hydrator\VisitorInterface;

/**
 * Class Element
 * @package Dgame\Soap
 */
class Element implements VisitableInterface, AssignableInterface
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var Attribute[]
     */
    private $attributes = [];
    /**
     * @var string
     */
    private $value;

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
    final public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return bool
     */
    final public function hasValue(): bool
    {
        return $this->value !== null;
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
     * @param Attribute $attribute
     */
    final public function setAttribute(Attribute $attribute)
    {
        $this->attributes[] = $attribute;
    }

    /**
     * @param Attribute[] $attributes
     */
    final public function setAttributes(array $attributes)
    {
        $this->attributes = [];
        foreach ($attributes as $attribute) {
            $this->setAttribute($attribute);
        }
    }

    /**
     * @return Attribute[]
     */
    final public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @return bool
     */
    final public function hasAttributes(): bool
    {
        return !empty($this->attributes);
    }

    /**
     * @return string
     */
    final public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param VisitorInterface $visitor
     */
    public function accept(VisitorInterface $visitor)
    {
        $visitor->visitElement($this);
    }
}