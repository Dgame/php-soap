<?php

namespace Dgame\Soap;

/**
 * Interface PrefixableInterface
 * @package Dgame\Soap\Hydrator
 */
interface PrefixableInterface
{
    /**
     * @param string $prefix
     */
    public function setPrefix(string $prefix);

    /**
     * @return string
     */
    public function getPrefix(): string;

    /**
     * @return bool
     */
    public function hasPrefix(): bool;
}