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
}