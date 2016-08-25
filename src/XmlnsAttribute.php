<?php

namespace Dgame\Soap;

use Dgame\Soap\Visitor\AttributeVisitor;

/**
 * Class XmlnsAttribute
 * @package Dgame\Soap
 */
class XmlnsAttribute extends XmlAttribute
{
    /**
     * XmlnsAttribute constructor.
     *
     * @param string $prefix
     * @param string $value
     */
    public function __construct(string $prefix, string $value)
    {
        parent::__construct('xmlns', $value, $prefix);
    }

    /**
     * @param AttributeVisitor $visitor
     */
    public function accept(AttributeVisitor $visitor)
    {
        $visitor->visitXmlnsAttribute($this);
    }
}
