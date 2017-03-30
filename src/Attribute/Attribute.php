<?php

namespace Dgame\Soap\Attribute;

use Dgame\Soap\Hydrator\Attribute\AttributeHydratorInterface;
use Dgame\Soap\Hydrator\Attribute\AttributeHydrogenableInterface;

/**
 * Class Attribute
 * @package Dgame\Soap\Attribute
 */
class Attribute implements AttributeHydrogenableInterface
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
        if ($value !== null) {
            $this->setValue($value);
        }
    }

    /**
     * @return null|string
     */
    final public function getValue(): ?string
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
     * @return string
     */
    final public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param AttributeHydratorInterface $hydrator
     */
    public function hydration(AttributeHydratorInterface $hydrator)
    {
        $hydrator->visitAttribute($this);
    }
}