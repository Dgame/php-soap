<?php

namespace Dgame\Soap\Attribute;

use Dgame\Soap\Visitor\AttributeVisitorInterface;

/**
 * Class SoapAttribute
 * @package Dgame\Soap\Attribute
 */
class SoapAttribute extends XmlAttribute
{
    /**
     * SoapAttribute constructor.
     *
     * @param string $prefix
     * @param string $value
     */
    public function __construct(string $prefix, string $value)
    {
        parent::__construct('soap', $value, $prefix);
    }

    /**
     * @param AttributeVisitorInterface $visitor
     */
    public function accept(AttributeVisitorInterface $visitor)
    {
        $visitor->visitSoapAttribute($this);
    }
}