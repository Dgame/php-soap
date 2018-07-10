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
use DOMXPath;

/**
 * Class BuiltinToPackageTranslator
 * @package Soap\Translator
 */
final class BuiltinToPackageTranslator
{
    /**
     * @var array
     */
    private $attributes = [];

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
        ['prefix' => $prefix, 'name' => $name] = $this->extractDefinition($attr);

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
        $this->importAttributes($node, $element);

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
        $this->importAttributes($node, $element);

        return $element;
    }

    /**
     * @param DOMNode             $node
     * @param XmlElementInterface $element
     */
    private function importAttributes(DOMNode $node, XmlElementInterface $element): void
    {
        foreach ($this->getOwnXmlnsAttributes($node) as $attribute => $value) {
            $element->setAttribute(new XmlnsAttribute($attribute, $value));
        }

        $this->setAttributes($node->attributes, $element);
    }

    /**
     * @param DOMNode $node
     * @param string  $attribute
     *
     * @return bool
     */
    private function hasParentAttributeDefinition(DOMNode $node, string $attribute): bool
    {
        if ($node->parentNode === null) {
            return false;
        }

        return array_key_exists($attribute, $this->getXmlnsAttributes($node->parentNode));
    }

    /**
     * @param DOMNode $node
     *
     * @return array
     */
    private function getOwnXmlnsAttributes(DOMNode $node): array
    {
        return array_filter($this->getXmlnsAttributes($node), function (string $attr) use ($node): bool {
            return !$this->hasParentAttributeDefinition($node, $attr);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @param DOMNode $node
     *
     * @return array
     */
    private function getXmlnsAttributes(DOMNode $node): array
    {
        if ($node->ownerDocument === null) {
            return [];
        }

        $name = $node->localName;
        if (!array_key_exists($name, $this->attributes)) {
            $attributes = [];

            $xpath = new DOMXPath($node->ownerDocument);
            foreach ($xpath->query('namespace::*', $node) as $attr) {
                $attributes[$attr->prefix] = $attr->namespaceURI;
            }

            $this->attributes[$name] = $attributes;
        }

        return $this->attributes[$name];
    }
}
