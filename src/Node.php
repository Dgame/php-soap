<?php

namespace Dgame\Soap;

use Dgame\Soap\Visitor\NodeAppendVisitor;
use DOMElement;

/**
 * Class Node
 * @package Dgame\Soap
 */
class Node extends Element
{
    /**
     * @var array
     */
    private $aliase = [];
    /**
     * @var Element[]
     */
    private $children = [];

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
     * @param array $change
     */
    public function onNamespaceChange(array $change)
    {
        parent::onNamespaceChange($change);

        foreach ($this->children as $child) {
            if (!$child->hasNamespace() || $child->hasEqualNamespace($change['old'])) {
                $child->setNamespace($change['new']);
            }
        }
    }

    /**
     * @param string $property
     * @param string $alias
     */
    final public function setPropertyAlias(string $property, string $alias)
    {
        $this->aliase[$property] = $alias;
    }

    /**
     * @param string $property
     *
     * @return bool
     */
    final public function hasPropertyAlias(string $property) : bool
    {
        return array_key_exists($property, $this->aliase);
    }

    /**
     * @param string $name
     *
     * @return string|null
     */
    final public function getPropertyAlias(string $name)
    {
        if ($this->hasPropertyAlias($name)) {
            return $this->aliase[$name];
        }

        return null;
    }

    /**
     * @param Node $node
     */
    final public function appendNode(Node $node)
    {
        $node->accept(new NodeAppendVisitor($this));
    }

    /**
     * @param Element $element
     */
    final public function appendElement(Element $element)
    {
        if (!$element->hasNamespace()) {
            $element->setNamespace($this->getNamespace());
        }

        $this->children[$element->getName()] = $element;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    final public function hasChild(string $name) : bool
    {
        return array_key_exists($name, $this->children);
    }

    /**
     * @param string $name
     *
     * @return Element|null
     */
    final public function getChild(string $name)
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