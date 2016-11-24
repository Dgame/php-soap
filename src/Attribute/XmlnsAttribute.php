<?php

namespace Dgame\Soap\Attribute;

use Dgame\Soap\Visitor\AttributeVisitorInterface;

/**
 * Class XmlnsAttribute
 * @package Dgame\Soap\Attribute
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
     * @return bool
     */
    public function isUsed(): bool
    {
        return $this->getUsage() > 0;
    }

    /**
     * @param AttributeVisitorInterface $visitor
     */
    public function accept(AttributeVisitorInterface $visitor)
    {
        $visitor->visitXmlnsAttribute($this);
    }
}