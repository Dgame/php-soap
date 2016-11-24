<?php

namespace Dgame\Soap;

use Dgame\Soap\Element\Element;

/**
 * Interface PrefixableInterface
 * @package Dgame\Soap
 */
interface PrefixableInterface
{
    /**
     * @return bool
     */
    public function hasPrefix(): bool;

    /**
     * @return string
     */
    public function getPrefix(): string;

    /**
     * @param Element $element
     */
    public function prefixUsedBy(Element $element);
}