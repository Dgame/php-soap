<?php

namespace Dgame\Soap\Attribute;

use Dgame\Soap\Visitor\AttributeVisitorInterface;

class XmlnsAttribute extends XmlAttribute
{
    public function __construct(string $prefix, string $value)
    {
        parent::__construct('xmlns', $value, $prefix);
    }

    public function isUsed(): bool
    {
        return $this->getUsage() > 0;
    }

    public function accept(AttributeVisitorInterface $visitor)
    {
        $visitor->visitXmlnsAttribute($this);
    }
}