<?php

namespace Dgame\Soap\Visitor;

use Dgame\Soap\Attribute\Attribute;
use Dgame\Soap\Attribute\XmlAttribute;

/**
 * Interface AttributeVisitorInterface
 * @package Dgame\Soap\Visitor
 */
interface AttributeVisitorInterface
{
    /**
     * @param Attribute $attribute
     */
    public function visitAttribute(Attribute $attribute);

    /**
     * @param XmlAttribute $attribute
     */
    public function visitXmlAttribute(XmlAttribute $attribute);
}