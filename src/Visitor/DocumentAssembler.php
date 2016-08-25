<?php

namespace Dgame\Soap\Visitor;

use Dgame\Soap\Element;
use Dgame\Soap\XmlElement;
use Dgame\Soap\XmlNode;
use DOMDocument;
use DOMElement;
use DOMNode;

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
     * @param DOMDocument $document
     */
    public function __construct(DOMDocument $document)
    {
        $this->document = $document;
        $this->node     = $document;
    }

    /**
     * @param XmlNode $node
     */
    public function visitXmlNode(XmlNode $node)
    {
        $child = $this->document->createElement($node->getNamespace());
        $this->assembleAttributes($node, $child);

        $this->node = $child;

        $this->assembleProperties($node);

        foreach ($node->getChildren() as $childNode) {
            $childNode->accept($this);
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

        $this->assembleAttributes($element, $child);
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

        $this->assembleAttributes($element, $child);
    }

    /**
     * @param Element    $child
     * @param DOMElement $element
     */
    private function assembleAttributes(Element $child, DOMElement $element)
    {
        $visitr = new AttributeAssembler($element);
        foreach ($child->getAttributes() as $attribute) {
            $attribute->accept($visitr);
        }

        $this->node->appendChild($element);
    }

    /**
     * @param XmlNode $node
     */
    private function assembleProperties(XmlNode $node)
    {
        foreach ($node->export() as $property => $alias) {
            if (is_int($property)) {
                $property = $alias;
            }

            $method = 'get' . ucfirst($property);
            if (method_exists($node, $method)) {
                $value = call_user_func([$node, $method]);
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
                $value->accept($this);
            }
        }
    }
}