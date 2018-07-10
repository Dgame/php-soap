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
     * @param ElementInterface $element
     */
    public function appendElementOnce(ElementInterface $element): void;

    /**
     * @return ElementInterface[]
     */
    public function getElements(): array;

    /**
     * @param int $index
     *
     * @return ElementInterface
     */
    public function getElementByIndex(int $index): ElementInterface;

    /**
     * @param string $name
     *
     * @return array
     */
    public function getElementsByName(string $name): array;

    /**
     * @param string   $name
     * @param callable $closure
     *
     * @return int
     */
    public function applyTo(string $name, callable $closure): int;
}
