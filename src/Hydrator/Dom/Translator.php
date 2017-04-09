<?php

namespace Dgame\Soap\Dom;

use Dgame\Soap\Attribute\XmlAttribute;
use Dgame\Soap\XmlElement;
use Dgame\Soap\XmlNode;
use DOMAttr;
use DOMDocument;
use DOMNode;

/**
 * Class DomTranslator
 * @package Dgame\Soap
 */
final class Translator
{
    /**
     * @return Translator
     */
    public static function new(): self
    {
        return new self();
    }

    /**
     * @param DOMDocument $document
     *
     * @return XmlElement[]|XmlNode[]
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
     * @return XmlNode|XmlElement|null
     */
    public function translateNode(DOMNode $node)
    {
        if ($node->nodeType !== XML_ELEMENT_NODE) {
            return null;
        }

        if ($this->isElement($node)) {
            return $this->createElement($node);
        }

        return $this->createNode($node);
    }

    /**
     * @param DOMNode $node
     *
     * @return bool
     */
    public function isElement(DOMNode $node): bool
    {
        return !$node->hasChildNodes() || ($node->childNodes->length === 1 && $node->firstChild->nodeType === XML_TEXT_NODE);
    }

    /**
     * @param DOMNode $node
     *
     * @return XmlElement
     */
    private function createElement(DOMNode $node): XmlElement
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
     * @param DOMNode    $node
     * @param XmlElement $element
     */
    private function setAttributes(DOMNode $node, XmlElement $element)
    {
        foreach ($node->attributes as $attribute) {
            $element->setAttribute($this->createAttribute($attribute));
        }
    }

    /**
     * @param DOMAttr $attr
     *
     * @return XmlAttribute
     */
    private function createAttribute(DOMAttr $attr): XmlAttribute
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
            } elseif ($childNode->nodeType === XML_TEXT_NODE) {
                $parent->setValue($childNode->nodeValue);
            }
        }
    }
}