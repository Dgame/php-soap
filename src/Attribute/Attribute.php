<?php

namespace Dgame\Soap\Attribute;

use Dgame\Soap\Visitor\AttributeVisitableInterface;
use Dgame\Soap\Visitor\AttributeVisitorInterface;

/**
 * Class Attribute
 * @package Dgame\Soap\Attribute
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
     * Attribute constructor.
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
    final public function setValue(string $value): void
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
     * @param AttributeVisitorInterface $visitor
     */
    public function accept(AttributeVisitorInterface $visitor): void
    {
        $visitor->visitAttribute($this);
    }
}