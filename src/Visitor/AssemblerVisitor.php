<?php

namespace Dgame\Soap\Visitor;

use Dgame\Soap\Element;
use Dgame\Soap\Node;
use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Class AssemblerVisitor
 * @package Dgame\Soap\Visitor
 */
final class AssemblerVisitor implements VisitorInterface
{
    /**
     * @var DOMDocument|null
     */
    private $document = null;
    /**
     * @var DOMNode|null
     */
    private $previous = null;

    /**
     * AssemblerVisitor constructor.
     *
     * @param DOMDocument $document
     */
    public function __construct(DOMDocument $document)
    {
        $this->document = $document;
        $this->previous = $document;
    }

    /**
     * @param Element $element
     *
     * @return DOMElement
     */
    private function create(Element $element) : DOMElement
    {
        if ($element->hasValue()) {
            return $this->document->createElement($element->getIdentifier(), $element->getValue());
        }

        return $this->document->createElement($element->getIdentifier());
    }

    /**
     * @param Node $node
     */
    public function visitNode(Node $node)
    {
        $child = $this->create($node);

        foreach ($node->getAttributes() as $attributes) {
            $attributes->assembleIn($child);
        }

        $this->previous->appendChild($child);
        $this->previous = $child;

        foreach ($node->getElements() as $element) {
            $element->accept($this);
        }
    }

    /**
     * @param Element $element
     */
    public function visitElement(Element $element)
    {
        $child = $this->create($element);

        foreach ($element->getAttributes() as $attributes) {
            $attributes->assembleIn($child);
        }

        $this->previous->appendChild($child);
    }
}