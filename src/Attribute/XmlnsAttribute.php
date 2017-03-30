<?php

namespace Dgame\Soap\Attribute;

/**
 * Class XmlnsAttribute
 * @package Dgame\Soap\Dom
 */
final class XmlnsAttribute extends XmlAttribute
{
    /**
     * XmlnsAttribute constructor.
     *
     * @param string      $name
     * @param string|null $value
     */
    public function __construct(string $name, string $value = null)
    {
        parent::__construct($name, $value, 'xmlns');
    }
}