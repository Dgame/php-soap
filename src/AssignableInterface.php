<?php

namespace Dgame\Soap;

/**
 * Interface AssignableInterface
 * @package Dgame\Soap
 */
interface AssignableInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getValue(): string;

    /**
     * @param string $value
     */
    public function setValue(string $value);

    /**
     * @return bool
     */
    public function hasValue(): bool;
}