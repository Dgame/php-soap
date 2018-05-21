<?php

namespace Dgame\Soap\Attribute;

use Dgame\Soap\PrefixTrait;
use Dgame\Soap\Visitor\AttributeVisitorInterface;

/**
 * Class XmlAttribute
 * @package Soap\Attribute
 */
class XmlAttribute extends Attribute implements XmlAttributeInterface
{
    use PrefixTrait;

    /**
     * XmlAttribute constructor.
     *
     * @param string $name
     * @param        $value
     */
    public function __construct(string $name, $value)
    {
        parent::__construct($name, $value);
    }

    /**
     * @param AttributeVisitorInterface $visitor
     */
    public function accept(AttributeVisitorInterface $visitor): void
    {
        $visitor->visitXmlAttribute($this);
    }
}
