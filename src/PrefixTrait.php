<?php

namespace Dgame\Soap;

/**
 * Class PrefixTrait
 * @package Dgame\Soap
 */
trait PrefixTrait
{
    /**
     * @var string
     */
    private $prefix;

    /**
     * @return bool
     */
    final public function hasPrefix(): bool
    {
        return !empty($this->prefix);
    }

    /**
     * @return string
     */
    final public function getPrefix(): string
    {
        return $this->prefix;
    }
}