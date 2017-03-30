<?php

namespace Dgame\Soap\Hydrator\Attribute;

use Dgame\Soap\Attribute\Attribute;
use Dgame\Soap\Attribute\XmlAttribute;
use Dgame\Soap\Element;
use DOMDocument;
use DOMElement;

/**
 * Class AttributeHydrator
 * @package Dgame\Soap\Hydrator\Attribute
 */
final class AttributeHydrator implements AttributeHydratorInterface
{
    /**
     * @var DOMElement
     */
    private $element;

    /**
     * AttributeHydrator constructor.
     *
     * @param Element     $element
     * @param DOMDocument $document
     */
    public function __construct(Element $element, DOMDocument $document)
    {
        $this->element = $document->createElement($element->getName(), $element->getValue());
        foreach ($element->getAttributes() as $attribute) {
            $attribute->accept($this);
        }
    }

    /**
     * @return DOMElement
     */
    public function getElement(): DOMElement
    {
        return $this->element;
    }

    /**
     * @param Attribute $attribute
     */
    public function visitAttribute(Attribute $attribute)
    {
        $this->element->setAttribute($attribute->getName(), $attribute->getValue());
    }

    /**
     * @param XmlAttribute $attribute
     */
    public function visitXmlAttribute(XmlAttribute $attribute)
    {
        $this->element->setAttribute($attribute->getName(), $attribute->getValue())->prefix = $attribute->getPrefix();
    }
}