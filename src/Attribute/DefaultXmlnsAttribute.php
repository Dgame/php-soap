<?php

namespace Dgame\Soap\Attribute;

use Dgame\Soap\Visitor\AttributeVisitorInterface;

class DefaultXmlnsAttribute extends XmlnsAttribute
{
    public function __construct(string $value)
    {
        parent::__construct('', $value);
    }

    public function isUsed(): bool
    {
        return true;
    }

    public function accept(AttributeVisitorInterface $visitor)
    {
        $visitor->visitDefaultXmlnsAttribute($this);
    }
}