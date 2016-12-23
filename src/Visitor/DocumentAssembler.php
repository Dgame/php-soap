<?php

namespace Dgame\Soap\Visitor;

use Dgame\Soap\Attribute\Attribute;
use Dgame\Soap\Element\Element;
use Dgame\Soap\Element\XmlElement;
use Dgame\Soap\Element\XmlNode;
use DOMNode;

/**
 * Class DocumentAssembler
 * @package Dgame\Soap\Visitor
 */
final class DocumentAssembler implements ElementVisitorInterface
{
    /**
     * @var DOMNode
     */
    private $owner;
    /**
     * @var DOMNode
     */
    private $node;

    /**
     * DocumentAssembler constructor.
     *
     * @param DOMNode $node
     */
    public function __construct(DOMNode $node)
    {
        $this->owner = $node->ownerDocument ?? $node;
        $this->node  = $node;
    }

    /**
     * @param XmlNode $node
     */
    public function visitXmlNode(XmlNode $node)
    {
        $child     = $this->owner->createElement($node->getNamespace());
        $assembler = new self($child);

        $this->assembleAttributes($node->getAttributes(), new AttributeAssembler($child));
        $this->node->appendChild($child);

        foreach ($node->getElements() as $childNode) {
            $childNode->accept($assembler);
        }
    }

    /**
     * @param XmlElement $element
     */
    public function visitXmlElement(XmlElement $element)
    {
        if ($element->hasValue()) {
            $child = $this->owner->createElement($element->getNamespace(), $element->getValue());
        } else {
            $child = $this->owner->createElement($element->getNamespace());
        }

        $this->node->appendChild($child);
        $this->assembleAttributes($element->getAttributes(), new AttributeAssembler($child));
    }

    /**
     * @param Element $element
     */
    public function visitElement(Element $element)
    {
        if ($element->hasValue()) {
            $child = $this->owner->createElement($element->getName(), $element->getValue());
        } else {
            $child = $this->owner->createElement($element->getName());
        }

        $this->node->appendChild($child);
        $this->assembleAttributes($element->getAttributes(), new AttributeAssembler($child));
    }

    /**
     * @param Attribute[]        $attributes
     * @param AttributeAssembler $assembler
     */
    private function assembleAttributes(array $attributes, AttributeAssembler $assembler)
    {
        foreach ($attributes as $attribute) {
            if ($attribute->isUsed()) {
                $attribute->accept($assembler);
            }
        }
    }
}