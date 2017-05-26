<?php

namespace Dgame\Soap\Visitor;

/**
 * Interface ElementVisitableInterface
 * @package Dgame\Soap\Visitor
 */
interface ElementVisitableInterface
{
    /**
     * @param ElementVisitorInterface $visitor
     */
    public function accept(ElementVisitorInterface $visitor);
}