<?php

namespace Dgame\Soap\Attribute;

/**
 * Class SoapAttribute
 * @package Soap\Attribute
 */
final class SoapAttribute extends XmlAttribute
{
    /**
     * SoapAttribute constructor.
     *
     * @param string $name
     * @param string $value
     */
    public function __construct(string $name, string $value)
    {
        parent::__construct($name, $value);

        $this->setPrefix('soap');
    }
}