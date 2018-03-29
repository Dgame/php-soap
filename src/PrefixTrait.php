<?php

namespace Dgame\Soap;

/**
 * Trait PrefixTrait
 * @package Soap
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
     * @param null|string $prefix
     */
    final public function setPrefix(?string $prefix): void
    {
        $this->prefix = $prefix;
    }

    /**
     * @return null|string
     */
    final public function getPrefix(): ?string
    {
        return $this->prefix;
    }
}