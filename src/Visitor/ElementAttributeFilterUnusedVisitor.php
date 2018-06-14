<?php

namespace Dgame\Soap\Visitor;

use Dgame\Soap\Attribute\AttributeInterface;
use Dgame\Soap\Attribute\XmlAttributeInterface;
use Dgame\Soap\Attribute\XmlnsAttribute;
use Dgame\Soap\Element\ElementInterface;
use Dgame\Soap\Element\XmlElementInterface;
use Dgame\Soap\Element\XmlNodeInterface;

/**
 * Class AttributeUsageVisitor
 * @package Dgame\Soap\Visitor
 */
final class ElementAttributeFilterUnusedVisitor implements ElementVisitorInterface, AttributeVisitorInterface
{
    /**
     * @var ElementInterface
     */
    private $element;

    /**
     * @param ElementInterface $element
     */
    public function visitElement(ElementInterface $element): void
    {
        $this->element = $element;

        foreach ($element->getAttributes() as $attribute) {
            $attribute->accept($this);
        }

        $this->element = null;
    }

    /**
     * @param XmlElementInterface $element
     */
    public function visitXmlElement(XmlElementInterface $element): void
    {
        $this->visitElement($element);
    }

    /**
     * @param XmlNodeInterface $node
     */
    public function visitXmlNode(XmlNodeInterface $node): void
    {
        $this->visitElement($node);
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
        if (!$attribute->isPrefixUsed()) {
            $this->element->removeAttributeByName($attribute->getName());
        }
    }

    /**
     * @param XmlnsAttribute $attribute
     */
    public function visitXmlnsAttribute(XmlnsAttribute $attribute): void
    {
        $this->visitXmlAttribute($attribute);
    }
}
