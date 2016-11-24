<?php

namespace Dgame\Soap\Attribute;

use Dgame\Soap\Visitor\AttributeVisitorInterface;

/**
 * Class DefaultXmlnsAttribute
 * @package Dgame\Soap\Attribute
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
     * @return bool
     */
    public function isUsed(): bool
    {
        return true;
    }

    /**
     * @param AttributeVisitorInterface $visitor
     */
    public function accept(AttributeVisitorInterface $visitor)
    {
        $visitor->visitDefaultXmlnsAttribute($this);
    }
}
