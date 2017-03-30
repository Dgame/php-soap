<?php

namespace Dgame\Soap\Hydrator;

use Dgame\Soap\Attribute\Attribute;
use Dgame\Soap\Element;

/**
 * Interface HydratInterface
 * @package Dgame\Soap\Hydrator
 */
interface HydratableInterface
{
    /**
     * @param Attribute $attribute
     */
    public function assignAttribute(Attribute $attribute);

    /**
     * @param Element $element
     */
    public function assignElement(Element $element);

    /**
     * @param HydratableInterface $hydratable
     *
     * @return bool
     */
    public function assignHydratable(self $hydratable): bool;

    /**
     * @param string $name
     * @param string $value
     */
    public function assign(string $name, string $value);

    /**
     * @return string
     */
    public function getClassName(): string;
}