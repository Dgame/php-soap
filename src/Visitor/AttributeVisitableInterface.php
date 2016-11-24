<?php

namespace Dgame\Soap\Visitor;

/**
 * Interface AttributeVisitableInterface
 * @package Dgame\Soap\Visitor
 */
interface AttributeVisitableInterface
{
    /**
     * @param AttributeVisitorInterface $visitor
     */
    public function accept(AttributeVisitorInterface $visitor);
}