<?php

namespace Dgame\Soap\Hydrator\Attribute;

/**
 * Interface AttributeHydrateInterface
 * @package Dgame\Soap\Hydrator\Attribute
 */
interface AttributeHydrateInterface
{
    /**
     * @param AttributeHydratorInterface $hydrator
     */
    public function hydration(AttributeHydratorInterface $hydrator);
}