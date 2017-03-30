<?php

namespace Dgame\Soap\Hydrator;

/**
 * Interface HydrateInterface
 * @package Dgame\Soap\Hydrator
 */
interface HydrateInterface
{
    /**
     * @param HydratorInterface $hydrator
     */
    public function hydration(HydratorInterface $hydrator);
}