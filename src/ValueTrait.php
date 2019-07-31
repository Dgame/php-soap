<?php

namespace Dgame\Soap;

/**
 * Trait ValueTrait
 * @package Soap
 */
trait ValueTrait
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @return bool
     */
    final public function hasValue(): bool
    {
        return is_string($this->value) ? !empty($this->value) : $this->value !== null;
    }

    /**
     * @return mixed
     */
    final public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    final public function setValue($value): void
    {
        $this->value = is_string($value) ? trim($value) : $value;
    }
}
