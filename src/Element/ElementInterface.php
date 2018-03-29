<?php

namespace Dgame\Soap\Element;

use Dgame\Soap\Attribute\AttributeInterface;
use Dgame\Soap\NamedInterface;
use Dgame\Soap\ValuedInterface;
use Dgame\Soap\Visitor\ElementVisitableInterface;

/**
 * Interface ElementInterface
 * @package Soap\Element
 */
interface ElementInterface extends NamedInterface, ValuedInterface, ElementVisitableInterface
{
    /**
     * @return bool
     */
    public function hasAttributes(): bool;

    /**
     * @return AttributeInterface[]
     */
    public function getAttributes(): array;

    /**
     * @param AttributeInterface $attribute
     */
    public function setAttribute(AttributeInterface $attribute): void;
}