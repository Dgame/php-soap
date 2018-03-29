<?php

namespace Dgame\Soap;

/**
 * Interface PrefixedInterface
 * @package Soap
 */
interface PrefixedInterface extends NamedInterface
{
    /**
     * @return bool
     */
    public function hasPrefix(): bool;

    /**
     * @param null|string $prefix
     */
    public function setPrefix(?string $prefix): void;

    /**
     * @return null|string
     */
    public function getPrefix(): ?string;
}