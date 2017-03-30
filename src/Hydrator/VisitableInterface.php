<?php

namespace Dgame\Soap\Hydrator;

/**
 * Interface HydrateInterface
 * @package Dgame\Soap\Hydrator
 */
interface VisitableInterface
{
    /**
     * @param VisitorInterface $hydrator
     */
    public function accept(VisitorInterface $hydrator);
}