<?php

namespace Dgame\Soap;

use Dgame\Soap\Visitor\AttributeVisitor;

/**
 * Class DefaultXmlnsAttribute
 * @package Dgame\Soap
 */
class DefaultXmlnsAttribute extends XmlnsAttribute
{
    /**
     * DefaultXmlnsAttribute constructor.
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        parent::__construct('', $value);
    }

    /**
     * @param AttributeVisitor $visitor
     */
    public function accept(AttributeVisitor $visitor)
    {
        $visitor->visitDefaultXmlnsAttribute($this);
    }
}