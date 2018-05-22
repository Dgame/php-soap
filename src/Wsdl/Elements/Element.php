<?php

namespace Dgame\Soap\Wsdl\Elements;

use DOMElement;
use function Dgame\Ensurance\enforce;

/**
 * Class Element
 * @package Dgame\Soap\Wsdl\Elements
 */
class Element
{
    /**
     * @var DOMElement
     */
    private $element;

    /**
     * Element constructor.
     *
     * @param DOMElement $element
     */
    public function __construct(DOMElement $element)
    {
        $this->element = $element;
    }

    /**
     * @param SimpleType|null $simple
     *
     * @return bool
     */
    public function isSimpleType(SimpleType &$simple = null): bool
    {
        $simple = null;

        return false;
    }

    /**
     * @param ComplexType|null $complex
     *
     * @return bool
     */
    public function isComplexType(ComplexType &$complex = null): bool
    {
        $complex = null;

        return false;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    final public function hasAttribute(string $name): bool
    {
        return $this->element->hasAttribute($name);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    final public function getAttribute(string $name): string
    {
        return $this->element->getAttribute($name);
    }

    /**
     * @return DOMElement
     */
    final public function getDomElement(): DOMElement
    {
        return $this->element;
    }

    /**
     * @return SimpleType
     */
    final public function getSimpleType(): SimpleType
    {
        if ($this->isSimpleType($simple)) {
            return $simple;
        }

        $nodes = $this->getDomElement()->getElementsByTagName('simpleType');
        enforce($nodes->length !== 0)->orThrow('There are no nodes with name Simple-Types');
        enforce($nodes->length === 1)->orThrow('There are multiple nodes with name Simple-Types');

        return new SimpleType($nodes->item(0));
    }

    /**
     * @return ComplexType
     */
    final public function getComplexType(): ComplexType
    {
        if ($this->isComplexType($complex)) {
            return $complex;
        }

        $nodes = $this->getDomElement()->getElementsByTagName('complexType');
        enforce($nodes->length !== 0)->orThrow('There are no nodes with name Complex-Types');
        enforce($nodes->length === 1)->orThrow('There are multiple nodes with name Complex-Types');

        return new ComplexType($nodes->item(0));
    }
}
