<?php

namespace Dgame\Soap\Visitor;

use Dgame\Soap\Element;
use Dgame\Soap\Node;
use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Class DocumentAssembler
 * @package Dgame\Soap\Visitor
 */
final class DocumentAssembler implements NodeVisitorInterface
{
    /**
     * @var DOMDocument|null
     */
    private $document = null;
    /**
     * @var DOMNode|null
     */
    private $node = null;

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
     * @param Node $node
     */
    public function visitNode(Node $node)
    {
        $child = $this->document->createElement($node->getNamespace());

        $this->append($node, $child);
        $this->node = $child;
        $this->assembleProperties($node);

        foreach ($node->getChildren() as $childNode) {
            $childNode->accept($this);
        }
    }

    /**
     * @param Element $element
     */
    public function visitElement(Element $element)
    {
        if ($element->hasValue()) {
            $child = $this->document->createElement($element->getNamespace(), $element->getValue());
        } else {
            $child = $this->document->createElement($element->getNamespace());
        }

        $this->append($element, $child);
        $this->assembleProperties($element);
    }

    /**
     * @param Node       $node
     * @param DOMElement $element
     */
    private function append(Node $node, DOMElement $element)
    {
        foreach ($node->getAttributes() as $attribute) {
            $element->setAttribute($attribute->getName(), $attribute->getValue());
        }

        $this->node->appendChild($element);
    }

    /**
     * @param Node $node
     */
    private function assembleProperties(Node $node)
    {
        foreach ($node->getPropertyExport() as $property => $alias) {
            if (is_int($property)) {
                $property = $alias;
            }

            $method = 'get' . ucfirst($property);
            if (method_exists($node, $method)) {
                $value = call_user_func([$node, $method]);
                if ($value === null) {
                    continue;
                }

                if (!$value instanceof Node) {
                    $name = ucfirst($alias);
                    if (!is_string($value)) {
                        $value = var_export($value, true);
                    }

                    $value = new Element($name, $value);
                }

                $value->inheritPrefix($node);
                $value->accept($this);
            }
        }
    }
}