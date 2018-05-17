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
     * @param string $name
     *
     * @return array
     */
    public function getElementsByName(string $name): array;
}
