<?php

namespace Dgame\Soap\Wsdl\Elements;

use DOMElement;
use function Dgame\Ensurance\enforce;

/**
 * Class Element
 * @package Dgame\Soap\Wsdl\Elements
 */
final class Element
{
    /**
     * @var DOMElement
     */
    private $element;

    /**
     * Element constructor.
     *
     * @param DOMElement $node
     */
    public function __construct(DOMElement $node)
    {
        $this->element = $node;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasAttribute(string $name): bool
    {
        return $this->element->hasAttribute($name);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function getAttribute(string $name): string
    {
        return $this->element->getAttribute($name);
    }

    /**
     * @return DOMElement
     */
    public function getElement(): DOMElement
    {
        return $this->element;
    }

    /**
     * @param string $name
     *
     * @return Element
     */
    public function getOneElementByName(string $name): self
    {
        $elements = $this->getAllElementsByName($name);

        enforce(count($elements) !== 0)->orThrow('There are no nodes with name %s', $name);
        enforce(count($elements) === 1)->orThrow('There are multiple nodes with name %s', $name);

        return array_pop($elements);
    }

    /**
     * @param string $name
     *
     * @return Element[]
     */
    public function getAllElementsByName(string $name): array
    {
        $elements = [];

        $nodes = $this->element->getElementsByTagName($name);
        for ($i = 0, $c = $nodes->length; $i < $c; $i++) {
            $node = $nodes->item($i);

            $elements[$node->getAttribute('name')] = new self($node);
        }

        return $elements;
    }
}
