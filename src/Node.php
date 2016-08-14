<?php

namespace Dgame\Soap;

use DOMElement;

/**
 * Class Node
 * @package Dgame\Soap
 */
class Node extends Element
{
    /**
     * @var Element[]
     */
    private $children = [];

    use PropertyAliasTrait;

    /**
     * Node constructor.
     *
     * @param string|null $name
     * @param string|null $namespace
     */
    public function __construct(string $name = null, string $namespace = null)
    {
        parent::__construct($name, null, $namespace);
    }

    /**
     * @param Node $node
     */
    public function appendNode(Node $node)
    {
        $node->accept(new NodeAppendVisitor($this));
    }

    /**
     * @param Element $element
     */
    public function appendElement(Element $element)
    {
        $this->children[$element->getName()] = $element;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasChild(string $name) : bool
    {
        return array_key_exists($name, $this->children);
    }

    /**
     * @param string $name
     *
     * @return Element|null
     */
    public function getChild(string $name)
    {
        if ($this->hasChild($name)) {
            return $this->children[$name];
        }

        return null;
    }

    /**
     * @param DOMElement $element
     * @param DOMElement $child
     */
    protected function afterAssemble(DOMElement $element, DOMElement $child)
    {
        parent::afterAssemble($element, $child);

        $this->assembleChildrenIn($child);
    }

    /**
     * @param DOMElement $element
     */
    final protected function assembleChildrenIn(DOMElement $element)
    {
        foreach ($this->children as $child) {
            if ($this->hasNamespace() && !$child->hasNamespace()) {
                $child->setNamespace($this->getNamespace());
            }

            $child->assembleIn($element);
        }
    }

    /**
     * @param NodeAppendVisitor $visitor
     */
    public function accept(NodeAppendVisitor $visitor)
    {
        $visitor->visitNode($this);
    }
}