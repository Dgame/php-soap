<?php

namespace Dgame\Soap\Hydrator;

/**
 * Interface HydrateInterface
 * @package Dgame\Soap\Hydrator
 */
interface VisitableInterface
{
    /**
     * @param VisitorInterface $visitor
     */
    public function accept(VisitorInterface $visitor);
}