<?php

namespace Dgame\Soap;

/**
 * Trait ClassNameTrait
 * @package Dgame\Soap
 */
trait ClassNameTrait
{
    /**
     * @return string
     */
    final public function getClassName() : string
    {
        return substr(strrchr(static::class, '\\'), 1);
    }
}