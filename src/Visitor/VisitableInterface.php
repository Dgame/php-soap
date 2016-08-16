<?php

namespace Dgame\Soap\Visitor;

/**
 * Interface VisitableInterface
 * @package Dgame\Soap\Visitor
 */
interface VisitableInterface
{
    /**
     * @param VisitorInterface $visitor
     *
     * @return mixed
     */
    public function accept(VisitorInterface $visitor);
}