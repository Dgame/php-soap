<?php

namespace Dgame\Soap;

/**
 * Trait NameTrait
 * @package Soap
 */
trait NameTrait
{
    /**
     * @var string
     */
    private $name;

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
    final public function hasName(): bool
    {
        return !empty($this->name);
    }
}
