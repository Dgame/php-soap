<?php

namespace Dgame\Soap\Attribute;

use Dgame\Soap\AssignableInterface;
use Dgame\Soap\Hydrator\VisitableInterface;
use Dgame\Soap\Hydrator\VisitorInterface;

/**
 * Class Attribute
 * @package Dgame\Soap\Attribute
 */
class Attribute implements VisitableInterface, AssignableInterface
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
        $visitor->visitAttribute($this);
    }
}