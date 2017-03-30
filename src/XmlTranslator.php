<?php

namespace Dgame\Soap;

use Dgame\Soap\Attribute\Attribute;
use Dgame\Soap\Attribute\XmlAttribute;
use DOMAttr;
use DOMDocument;
use DOMNode;

/**
 * Class XmlTranslator
 * @package Dgame\Soap
 */
final class XmlTranslator
{
    /**
     * @param DOMDocument $document
     *
     * @return Element[]|XmlNode[]
     */
    public function translateDocument(DOMDocument $document): array
    {
        $elements = [];
        foreach ($document->childNodes as $node) {
            $element = $this->translateNode($node);
            if ($element !== null) {
                $elements[] = $element;
            }
        }

        return $elements;
    }

    /**
     * @param DOMNode $node
     *
     * @return XmlNode|Element|null
     */
    public function translateNode(DOMNode $node)
    {
        if ($node->nodeType === XML_ELEMENT_NODE) {
            if ($this->isElementNode($node)) {
                return $this->createElement($node);
            }

            return $this->createNode($node);
        }

        return null;
    }

    /**
     * @param DOMNode $node
     *
     * @return Element
     */
    private function createElement(DOMNode $node): Element
    {
        $element = new XmlElement($node->localName, $node->nodeValue, $node->prefix);
        $this->setAttributes($node, $element);

        return $element;
    }

    /**
     * @param DOMNode $node
     *
     * @return XmlNode
     */
    private function createNode(DOMNode $node): XmlNode
    {
        $element = new XmlNode($node->localName, null, $node->prefix);
        $this->setAttributes($node, $element);
        $this->appendChildNodes($node, $element);

        return $element;
    }

    /**
     * @param DOMNode $node
     * @param Element $element
     */
    private function setAttributes(DOMNode $node, Element $element)
    {
        foreach ($node->attributes as $attribute) {
            $element->setAttribute($this->createAttribute($attribute));
        }
    }

    /**
     * @param DOMAttr $attr
     *
     * @return Attribute
     */
    private function createAttribute(DOMAttr $attr): Attribute
    {
        return new XmlAttribute($attr->name, $attr->value, $attr->prefix);
    }

    /**
     * @param DOMNode $node
     * @param XmlNode $parent
     */
    private function appendChildNodes(DOMNode $node, XmlNode $parent)
    {
        foreach ($node->childNodes as $childNode) {
            $child = $this->translateNode($childNode);
            if ($child !== null) {
                $parent->appendChild($child);
            }
        }
    }

    /**
     * @param DOMNode $node
     *
     * @return bool
     */
    private function isElementNode(DOMNode $node): bool
    {
        return $node->childNodes->length === 0 || ($node->childNodes->length === 1 && $node->firstChild->nodeType === XML_TEXT_NODE);
    }
}