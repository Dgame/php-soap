<?php

namespace Dgame\Soap\Attribute;

use Dgame\Soap\Visitor\AttributeVisitorInterface;

class SoapAttribute extends XmlAttribute
{
    public function __construct(string $value, string $prefix = null)
    {
        parent::__construct('soap', $value, $prefix);
    }

    public function accept(AttributeVisitorInterface $visitor)
    {
        $visitor->visitSoapAttribute($this);
    }
}