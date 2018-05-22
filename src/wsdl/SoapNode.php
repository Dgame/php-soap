<?php

namespace Dgame\Soap\Wsdl;

/**
 * Class SoapNode
 * @package Dgame\Soap\Wsdl
 */
final class SoapNode extends SoapElement
{
    /**
     * @var SoapElement[]
     */
    private $elements = [];

    /**
     * @param SoapNode|null $node
     *
     * @return bool
     */
    public function isSoapNode(self &$node = null): bool
    {
        $node = $this;

        return true;
    }

    /**
     * @param SoapElement[] $elements
     */
    public function setChildElements(array $elements): void
    {
        foreach ($elements as $child) {
            $this->appendChildElement($child);
        }
    }

    /**
     * @param SoapElement $element
     */
    public function appendChildElement(SoapElement $element): void
    {
        $this->elements[$element->getName()] = $element;
    }

    /**
     * @return SoapElement[]
     */
    public function getChildElements(): array
    {
        return $this->elements;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasChildElementWithName(string $name): bool
    {
        return array_key_exists($name, $this->elements);
    }

    /**
     * @param string $name
     *
     * @return SoapElement
     */
    public function getChildElementByName(string $name): SoapElement
    {
        return $this->elements[$name];
    }
}
