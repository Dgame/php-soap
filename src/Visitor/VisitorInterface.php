<?php

namespace Dgame\Soap\Visitor;

use Dgame\Soap\Element;
use Dgame\Soap\Node;

/**
 * Interface VisitorInterface
 * @package Dgame\Soap\Visitor
 */
interface VisitorInterface
{
    /**
     * @param Node $node
     */
    public function visitNode(Node $node);

    /**
     * @param Element $element
     */
    public function visitElement(Element $element);
}