<?php

namespace Dgame\Soap\Hydrator;

use Dgame\Soap\Attribute\AttributeInterface;
use Dgame\Soap\Element\ElementInterface;

/**
 * Interface HydratorStrategyInterface
 * @package Dgame\Soap\Hydrator
 */
interface HydratorStrategyInterface
{
    /**
     * @param string             $footprints
     * @param AttributeInterface $attribute
     */
    public function setAttribute(string $footprints, AttributeInterface $attribute): void;

    /**
     * @param string           $footprints
     * @param ElementInterface $element
     */
    public function pushElement(string $footprints, ElementInterface $element): void;

    /**
     *
     */
    public function popElement(): void;

    /**
     * @param string $footprint
     *
     * @return string
     */
    public function processFootprint(string $footprint): string;
}