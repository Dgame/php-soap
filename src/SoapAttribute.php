<?php

namespace Dgame\Soap;

use Dgame\Soap\Visitor\AttributeVisitorInterface;

/**
 * Class SoapAttribute
 * @package Dgame\Soap
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
        parent::__construct($prefix, $value, 'soap');
    }

    /**
     * @param AttributeVisitorInterface $visitor
     */
    public function accept(AttributeVisitorInterface $visitor)
    {
        $visitor->visitSoapAttribute($this);
    }
}