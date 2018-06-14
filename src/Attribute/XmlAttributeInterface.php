<?php

namespace Dgame\Soap\Attribute;

use Dgame\Soap\PrefixedInterface;

/**
 * Interface XmlAttributeInterface
 * @package Soap\Attribute
 */
interface XmlAttributeInterface extends PrefixedInterface, AttributeInterface
{
    /**
     * @return int
     */
    public function getPrefixUsage(): int;

    /**
     * @return bool
     */
    public function isPrefixUsed(): bool;

    /**
     *
     */
    public function incrementPrefixUsage(): void;
}
