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
     * @param int $index
     *
     * @return AttributeInterface
     */
    public function getAttributeByIndex(int $index): AttributeInterface;

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasAttributeWithName(string $name): bool;

    /**
     * @param string $name
     *
     * @return AttributeInterface
     */
    public function getAttributeByName(string $name): AttributeInterface;

    /**
     * @param AttributeInterface $attribute
     */
    public function setAttribute(AttributeInterface $attribute): void;

    /**
     * @param string $name
     *
     * @return AttributeInterface|null
     */
    public function removeAttributeByName(string $name): ?AttributeInterface;
}
