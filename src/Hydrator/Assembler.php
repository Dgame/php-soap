<?php

namespace Dgame\Soap\Hydrator;

use Dgame\Soap\Attribute\Attribute;
use Dgame\Soap\Attribute\XmlAttribute;
use Dgame\Soap\Element;
use Dgame\Soap\Visitor\AttributeVisitorInterface;
use Dgame\Soap\Visitor\ElementVisitorInterface;
use Dgame\Soap\XmlElement;
use Dgame\Soap\XmlNode;
use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Class Assembler
 * @package Dgame\Soap\Hydrator
 */
final class Assembler implements ElementVisitorInterface, AttributeVisitorInterface
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
     * @param DOMNode|null $node
     */
    public function __construct(DOMNode $node = null)
    {
        if ($node === null) {
            $this->node = $this->document = new DOMDocument('1.0', 'utf-8');
        } else {
            $this->node     = $node;
            $this->document = $node->ownerDocument ?? $this->node;
        }
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
     * @param DOMNode $node
     *
     * @return DOMNode
     */
    private function assemble(Element $element, DOMNode $node): DOMNode
    {
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
        $node = $this->document->createElement($element->getName(), $element->hasValue() ? $element->getValue() : null);
        $this->assemble($element, $node);
    }

    /**
     * @param XmlElement $element
     */
    public function visitXmlElement(XmlElement $element)
    {
        $node = $this->document->createElement($element->getPrefixedName(), $element->hasValue() ? $element->getValue() : null);
        $node = $this->assemble($element, $node);
        if ($element->hasPrefix()) {
            $node->prefix = $element->getPrefix();
        }
    }

    /**
     * @param XmlNode $node
     */
    public function visitXmlNode(XmlNode $node)
    {
        $this->visitXmlElement($node);

        foreach ($node->getElements() as $child) {
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
        $this->node->setAttribute($attribute->getPrefixedName(), $attribute->hasValue() ? $attribute->getValue() : null);
    }
}