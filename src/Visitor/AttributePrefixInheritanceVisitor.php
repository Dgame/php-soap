<?php

namespace Dgame\Soap\Visitor;

use Dgame\Soap\Attribute\Attribute;
use Dgame\Soap\Attribute\XmlAttribute;
use Dgame\Soap\XmlElement;

/**
 * Class AttributePrefixInheritanceVisitor
 * @package Dgame\Soap\Visitor
 */
final class AttributePrefixInheritanceVisitor implements AttributeVisitorInterface
{
    /**
     * @var XmlElement
     */
    private $element;

    /**
     * AttributePrefixInheritanceVisitor constructor.
     *
     * @param XmlElement $element
     */
    public function __construct(XmlElement $element)
    {
        $this->element = $element;
    }

    /**
     * @param Attribute $attribute
     */
    public function visitAttribute(Attribute $attribute)
    {
    }

    /**
     * @param XmlAttribute $attribute
     */
    public function visitXmlAttribute(XmlAttribute $attribute)
    {
        $this->inheritPrefix($attribute);
    }

    /**
     * @param XmlAttribute $attribute
     */
    private function inheritPrefix(XmlAttribute $attribute)
    {
        if (!$this->element->hasPrefix()) {
            $this->element->setPrefix($attribute->getName());
        }
    }
}