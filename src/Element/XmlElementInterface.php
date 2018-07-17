<?php

namespace Dgame\Soap\Element;

use Dgame\Soap\PrefixedInterface;

/**
 * Interface XmlElementInterface
 * @package Soap\Element
 */
interface XmlElementInterface extends PrefixedInterface, ElementInterface
{
    /**
     * @param string $prefix
     * @param string $uri
     */
    public function setNamespaceAttribute(string $prefix, string $uri): void;
}
