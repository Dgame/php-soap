<?php

namespace Dgame\Soap\Visitor;

use Dgame\Soap\Attribute\AttributeInterface;
use Dgame\Soap\Attribute\XmlAttributeInterface;
use Dgame\Soap\Attribute\XmlnsAttribute;

/**
 * Interface AttributeVisitorInterface
 * @package Soap\Visitor
 */
interface AttributeVisitorInterface
{
    /**
     * @param AttributeInterface $attribute
     */
    public function visitAttribute(AttributeInterface $attribute): void;

    /**
     * @param XmlAttributeInterface $attribute
     */
    public function visitXmlAttribute(XmlAttributeInterface $attribute): void;

    /**
     * @param XmlnsAttribute $attribute
     */
    public function visitXmlnsAttribute(XmlnsAttribute $attribute): void;
}