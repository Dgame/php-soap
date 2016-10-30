<?php

namespace Dgame\Soap;

/**
 * Class ClassNameTrait
 * @package Dgame\Soap
 */
trait ClassNameTrait
{
    /**
     * @return string
     */
    final public function getClassName(): string
    {
        return basename(str_replace('\\', '/', static::class));
    }
}