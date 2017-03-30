<?php

namespace Dgame\Soap\Hydrator\Attribute;

/**
 * Interface AttributeHydrogenableInterface
 * @package Dgame\Soap\Hydrator\Attribute
 */
interface AttributeHydrogenableInterface
{
    /**
     * @param AttributeHydratorInterface $hydrator
     */
    public function hydration(AttributeHydratorInterface $hydrator);
}