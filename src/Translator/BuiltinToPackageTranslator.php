<?php

namespace Dgame\Soap\Translator;

use Dgame\Soap\Attribute\XmlAttribute;
use Dgame\Soap\Attribute\XmlnsAttribute;
use Dgame\Soap\Element\XmlElement;
use Dgame\Soap\Element\XmlElementInterface;
use Dgame\Soap\Element\XmlNode;
use Dgame\Soap\Element\XmlNodeInterface;
use DOMAttr;
use DOMDocument;
use DOMNamedNodeMap;
use DOMNode;

/**
 * Class BuiltinToPackageTranslator
 * @package Soap\Translator
 */
final class BuiltinToPackageTranslator
{
    /**
     * @param DOMNode $node
     *
     * @return XmlElementInterface|null
     */
    public function translate(DOMNode $node): ?XmlElementInterface
    {
        if ($node->nodeType === XML_DOCUMENT_NODE) {
            /** @var DOMDocument $node */
            return $this->translateDocument($node);
        }

        return $this->translateNode($node);
    }

    /**
     * @param DOMDocument $document
     *
     * @return XmlElementInterface|null
     */
    private function translateDocument(DOMDocument $document): ?XmlElementInterface
    {
        return $this->translateNode($document->documentElement);
    }

    /**
     * @param DOMNode $node
     *
     * @return XmlElementInterface|null
     */
    private function translateNode(DOMNode $node): ?XmlElementInterface
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
    private static function isElement(DOMNode $node): bool
    {
        return !$node->hasChildNodes() || ($node->childNodes->length === 1 && $node->firstChild->nodeType === XML_TEXT_NODE);
    }

    /**
     * @param DOMNode $node
     *
     * @return XmlElementInterface
     */
    private function createElement(DOMNode $node): XmlElementInterface
    {
        return $this->createXmlElementFrom($node);
    }

    /**
     * @param DOMNode $node
     *
     * @return XmlElementInterface
     */
    private function createNode(DOMNode $node): XmlElementInterface
    {
        $element = $this->createXmlNodeFrom($node);
        $this->appendChildNodes($node, $element);

        return $element;
    }

    /**
     * @param DOMNamedNodeMap     $attributes
     * @param XmlElementInterface $element
     */
    private function setAttributes(DOMNamedNodeMap $attributes, XmlElementInterface $element): void
    {
        foreach ($attributes as $attribute) {
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
        list($prefix, $name) = $this->extractDefinition($attr);

        $attribute = new XmlAttribute($name, $attr->value);
        $attribute->setPrefix($prefix);

        return $attribute;
    }

    /**
     * @param DOMNode          $node
     * @param XmlNodeInterface $parent
     */
    private function appendChildNodes(DOMNode $node, XmlNodeInterface $parent): void
    {
        foreach ($node->childNodes as $childNode) {
            $child = $this->translateNode($childNode);
            if ($child !== null) {
                $parent->appendElement($child);
            } elseif ($childNode->nodeType === XML_TEXT_NODE) {
                $parent->setValue($childNode->nodeValue);
            }
        }
    }

    /**
     * @param DOMNode $node
     *
     * @return array
     */
    private function extractDefinition(DOMNode $node): array
    {
        $prefix = $node->prefix;
        $name   = $node->localName;
        if (empty($prefix) && strpos($name, ':') !== false) {
            $values = explode(':', $name);

            return array_combine(['prefix', 'name'], array_slice($values, 0, 2));
        }

        return ['prefix' => $prefix, 'name' => $name];
    }

    /**
     * @param DOMNode $node
     *
     * @return XmlElementInterface
     */
    private function createXmlElementFrom(DOMNode $node): XmlElementInterface
    {
        ['prefix' => $prefix, 'name' => $name] = $this->extractDefinition($node);

        $element = new XmlElement($name, $node->nodeValue);
        $element->setPrefix($prefix);
        $this->createElementFrom($node, $element);

        return $element;
    }

    /**
     * @param DOMNode $node
     *
     * @return XmlNodeInterface
     */
    private function createXmlNodeFrom(DOMNode $node): XmlNodeInterface
    {
        ['prefix' => $prefix, 'name' => $name] = $this->extractDefinition($node);

        $element = new XmlNode($name, null);
        $element->setPrefix($prefix);
        $this->createElementFrom($node, $element);

        return $element;
    }

    /**
     * @param DOMNode             $node
     * @param XmlElementInterface $element
     */
    private function createElementFrom(DOMNode $node, XmlElementInterface $element): void
    {
        $this->setAttributes($node->attributes, $element);

        if ($element->hasPrefix() && !empty($node->namespaceURI) && !$this->hasParentSameNamespace($node)) {
            $element->setAttribute(new XmlnsAttribute($element->getPrefix(), $node->namespaceURI));
        }
    }

    /**
     * @param DOMNode $node
     *
     * @return bool
     */
    private function hasParentSameNamespace(DOMNode $node): bool
    {
        $parent = $node->parentNode;
        while ($parent !== null) {
            if ($parent->namespaceURI === $node->namespaceURI) {
                return true;
            }
            $parent = $parent->parentNode;
        }

        return false;
    }
}