<?php

namespace Dgame\Soap;

/**
 * Interface ValuedInterface
 * @package Soap
 */
interface ValuedInterface
{
    /**
     * @return bool
     */
    public function hasValue(): bool;

    /**
     * @param mixed $value
     */
    public function setValue($value): void;

    /**
     * @return mixed
     */
    public function getValue();
}
