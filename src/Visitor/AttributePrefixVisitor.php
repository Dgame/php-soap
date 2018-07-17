<?php

namespace Dgame\Soap\Visitor;

use Dgame\Soap\Attribute\AttributeInterface;
use Dgame\Soap\Attribute\XmlAttributeInterface;
use Dgame\Soap\Attribute\XmlnsAttribute;
use Dgame\Soap\Element\XmlElementInterface;

/**
 * Class AttributePrefixVisitor
 * @package Dgame\Soap\Visitor
 */
final class AttributePrefixVisitor implements AttributeVisitorInterface
{
    /**
     * @var XmlElementInterface
     */
    private $element;

    /**
     * AttributePrefixVisitor constructor.
     *
     * @param XmlElementInterface $element
     */
    public function __construct(XmlElementInterface $element)
    {
        $this->element = $element;
    }

    /**
     * @param AttributeInterface $attribute
     */
    public function visitAttribute(AttributeInterface $attribute): void
    {
    }

    /**
     * @param XmlAttributeInterface $attribute
     */
    public function visitXmlAttribute(XmlAttributeInterface $attribute): void
    {
        $attribute->incrementPrefixUsage();
    }

    /**
     * @param XmlnsAttribute $attribute
     */
    public function visitXmlnsAttribute(XmlnsAttribute $attribute): void
    {
        $this->element->setPrefix($attribute->getName());
        $attribute->incrementPrefixUsage();
    }
}
