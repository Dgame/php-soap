<?php

namespace Dgame\Soap\Visitor;

use Dgame\Soap\Element;
use Dgame\Soap\Node;

/**
 * Interface NodeVisitorInterface
 * @package Dgame\Soap\Visitor
 */
interface NodeVisitorInterface
{
    /**
     * @param Element $element
     */
    public function visitElement(Element $element);

    /**
     * @param Node $node
     */
    public function visitNode(Node $node);
}