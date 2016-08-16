<?php

namespace Dgame\Soap\Visitor;

use Dgame\Soap\Element;
use Dgame\Soap\Node;
use ReflectionClass;
use ReflectionProperty;

/**
 * Class NodeAppendVisitor
 * @package Dgame\Soap\Visitor
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
            if ($value === null) {
                continue;
            }

            $this->assign($node, $property, $value);
        }

        $this->node->appendElement($node);
    }

    /**
     * @param Node               $node
     * @param ReflectionProperty $property
     * @param                    $value
     */
    private function assign(Node $node, ReflectionProperty $property, $value)
    {
        if ($value instanceof Element) {
            $value->accept(new self($node));
        } else {
            $name = $node->hasPropertyAlias($property->name) ? $node->getPropertyAlias($property->name) : ucfirst($property->name);
            if (!is_string($value)) {
                $value = var_export($value, true);
            }

            $node->appendElement(new Element($name, $value));
        }
    }

    /**
     * @param Element $element
     */
    public function visitElement(Element $element)
    {
        $this->node->appendElement($element);
    }
}