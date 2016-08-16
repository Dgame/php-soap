<?php

namespace Dgame\Soap;

use Dgame\Soap\Visitor\NodeAppendVisitor;
use Dgame\Soap\Visitor\VisitorInterface;

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
    private $elements = [];

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

        foreach ($this->elements as $element) {
            if (!$element->hasNamespace() || $element->hasEqualNamespace($change['old'])) {
                $element->setNamespace($change['new']);
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

        $this->elements[$element->getName()] = $element;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    final public function hasElement(string $name) : bool
    {
        return array_key_exists($name, $this->elements);
    }

    /**
     * @param string $name
     *
     * @return Element|null
     */
    final public function getElement(string $name)
    {
        if ($this->hasElement($name)) {
            return $this->elements[$name];
        }

        return null;
    }

    /**
     * @return Element[]
     */
    final public function getElements() : array
    {
        return $this->elements;
    }

    /**
     * @param VisitorInterface $visitor
     */
    public function accept(VisitorInterface $visitor)
    {
        $visitor->visitNode($this);
    }
}