<?php

namespace Dgame\Soap\Attribute;

/**
 * Class SoapAttribute
 * @package Dgame\Soap\Dom
 */
final class SoapAttribute extends XmlAttribute
{
    /**
     * SoapAttribute constructor.
     *
     * @param string      $name
     * @param string|null $value
     */
    public function __construct(string $name, string $value = null)
    {
        parent::__construct($name, $value, 'soap');
    }
}