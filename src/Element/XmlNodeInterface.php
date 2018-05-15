<?php

namespace Dgame\Soap\Element;

/**
 * Interface XmlNodeInterface
 * @package Soap\Element
 */
interface XmlNodeInterface extends XmlElementInterface
{
    /**
     * @return bool
     */
    public function hasElements(): bool;

    /**
     * @param ElementInterface $element
     */
    public function appendElement(ElementInterface $element): void;

    /**
     * @return ElementInterface[]
     */
    public function getElements(): array;

    /**
     * @param string                $name
     * @param ElementInterface|null $element
     *
     * @return bool
     */
    public function hasElementWithName(string $name, ElementInterface &$element = null): bool;

    /**
     * @param string $name
     *
     * @return ElementInterface|null
     */
    public function getElementByName(string $name): ?ElementInterface;

    /**
     * @param string        $name
     * @param callable|null $create
     *
     * @return ElementInterface
     */
    public function getOrSetElementByName(string $name, callable $create = null): ElementInterface;
}
