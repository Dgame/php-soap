<?php

namespace Dgame\Soap;

use ReflectionClass;
use ReflectionProperty;

/**
 * Class NodeAppendVisitor
 * @package Dgame\Soap
 */
final class NodeAppendVisitor
{
    /**
     * @var Node|null
     */
    private $node = null;

    /**
     * NodeAppendVisitor constructor.
     *
     * @param Node $node
     */
    public function __construct(Node $node)
    {
        $this->node = $node;
    }

    /**
     * @param Node $node
     */
    public function visitNode(Node $node)
    {
        $ref        = new ReflectionClass($node);
        $properties = $ref->getProperties(ReflectionProperty::IS_PUBLIC);
        foreach ($properties as $property) {
            $value = $property->getValue($node);
            if ($value instanceof Element) {
                $value->accept(new NodeAppendVisitor($node));
            } else {
                $name = $node->hasPropertyAlias($property->name) ? $node->getPropertyAlias($property->name) : ucfirst($property->name);
                $node->appendElement(new Element($name, $value));
            }
        }

        $this->node->appendElement($node);
    }

    /**
     * @param Element $element
     */
    public function visitElement(Element $element)
    {
        $this->node->appendElement($element);
    }
}