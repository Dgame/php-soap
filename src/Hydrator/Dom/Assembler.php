<?php

namespace Dgame\Soap\Hydrator\Dom;

use Dgame\Soap\Attribute\Attribute;
use Dgame\Soap\Attribute\XmlAttribute;
use Dgame\Soap\Element;
use Dgame\Soap\Hydrator\VisitorInterface;
use Dgame\Soap\XmlElement;
use Dgame\Soap\XmlNode;
use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Class Assembler
 * @package Dgame\Soap\Hydrator\Dom
 */
final class Assembler implements VisitorInterface
{
    /**
     * @var DOMDocument
     */
    private $document;
    /**
     * @var DOMElement
     */
    private $node;

    /**
     * Assembler constructor.
     *
     * @param DOMNode $node
     */
    public function __construct(DOMNode $node)
    {
        $this->node     = $node;
        $this->document = $node->ownerDocument ?? $this->node;
    }

    /**
     * @return DOMDocument
     */
    public function getDocument(): DOMDocument
    {
        return $this->document;
    }

    /**
     * @param Element $element
     *
     * @return DOMNode
     */
    private function assemble(Element $element): DOMNode
    {
        $node = $this->document->createElement($element->getName(), $element->hasValue() ? $element->getValue() : null);
        $this->node->appendChild($node);
        $this->node = $node;

        foreach ($element->getAttributes() as $attribute) {
            $attribute->accept($this);
        }

        return $node;
    }

    /**
     * @param Element $element
     */
    public function visitElement(Element $element)
    {
        $this->assemble($element);
    }

    /**
     * @param XmlElement $element
     */
    public function visitXmlElement(XmlElement $element)
    {
        $node = $this->assemble($element);
        if ($element->hasPrefix()) {
            $node->prefix = $element->getPrefix();
        }
    }

    /**
     * @param XmlNode $node
     */
    public function visitXmlNode(XmlNode $node)
    {
        $element = $this->assemble($node);
        if ($node->hasPrefix()) {
            $element->prefix = $node->getPrefix();
        }

        foreach ($node->getChildren() as $child) {
            $assembler = new self($this->node);
            $child->accept($assembler);
        }
    }

    /**
     * @param Attribute $attribute
     */
    public function visitAttribute(Attribute $attribute)
    {
        $this->node->setAttribute($attribute->getName(), $attribute->hasValue() ? $attribute->getValue() : null);
    }

    /**
     * @param XmlAttribute $attribute
     */
    public function visitXmlAttribute(XmlAttribute $attribute)
    {
        $attr = $this->node->setAttribute($attribute->getName(), $attribute->hasValue() ? $attribute->getValue() : null);
        if ($attribute->hasPrefix()) {
            $attr->prefix = $attribute->getPrefix();
        }
    }
}