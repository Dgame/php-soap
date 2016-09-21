<?php

namespace Dgame\Soap\Visitor;

use Dgame\Soap\Attribute;
use Dgame\Soap\Element;
use Dgame\Soap\XmlElement;
use Dgame\Soap\XmlNode;
use DOMDocument;
use DOMNode;
use ReflectionClass;

/**
 * Class DocumentAssembler
 * @package Dgame\Soap\Visitor
 */
final class DocumentAssembler implements ElementVisitor
{
    /**
     * @var DOMDocument
     */
    private $document;
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
        $this->document = $node->ownerDocument ?? $node;
        $this->node     = $node;
    }

    /**
     * @param XmlNode $node
     */
    public function visitXmlNode(XmlNode $node)
    {
        $child     = $this->document->createElement($node->getNamespace());
        $assembler = new self($child);

        $this->assembleAttributes($node->getAttributes(), new AttributeAssembler($child));
        $this->assembleProperties($node, $assembler);

        $this->node->appendChild($child);

        foreach ($node->getChildren() as $childNode) {
            $childNode->accept($assembler);
        }
    }

    /**
     * @param XmlElement $element
     */
    public function visitXmlElement(XmlElement $element)
    {
        if ($element->hasValue()) {
            $child = $this->document->createElement($element->getNamespace(), $element->getValue());
        } else {
            $child = $this->document->createElement($element->getNamespace());
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
            $child = $this->document->createElement($element->getName(), $element->getValue());
        } else {
            $child = $this->document->createElement($element->getName());
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

    /**
     * @param XmlNode           $node
     * @param DocumentAssembler $assembler
     */
    private function assembleProperties(XmlNode $node, DocumentAssembler $assembler)
    {
        foreach ($node->export() as $property => $alias) {
            if (is_int($property)) {
                $property = $alias;
            }

            $reflection = new ReflectionClass($node);
            $method     = 'get' . ucfirst($property);
            if ($reflection->hasMethod($method)) {
                $value = $reflection->getMethod($method)->invoke($node);
                if (empty($value)) {
                    continue;
                }

                if (!$value instanceof Element) {
                    $name = ucfirst($alias);
                    if (!is_string($value)) {
                        $value = var_export($value, true);
                    }
                    $value = new XmlElement($name, $value);
                }

                $value->inheritPrefix($node);
                $value->accept($assembler);
            }
        }
    }
}