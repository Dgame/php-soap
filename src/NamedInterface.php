<?php

namespace Dgame\Soap;

/**
 * Interface NamedInterface
 * @package Soap
 */
interface NamedInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return bool
     */
    public function hasName(): bool;
}
