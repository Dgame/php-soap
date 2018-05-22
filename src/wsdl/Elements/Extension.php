<?php

namespace Dgame\Soap\Wsdl\Elements;

use DOMElement;

/**
 * Class Extension
 * @package Dgame\Soap\Wsdl\Elements
 */
final class Extension
{
    /**
     * @var string
     */
    private $base;
    /**
     * @var string
     */
    private $prefix;
    /**
     * @var DOMElement
     */
    private $element;

    /**
     * Extension constructor.
     *
     * @param DOMElement $element
     * @param string     $extension
     */
    public function __construct(DOMElement $element, string $extension)
    {
        $this->element = $element;

        [$this->prefix, $this->base] = explode(':', $extension);
    }

    /**
     * @return string
     */
    public function getPrefixedName(): string
    {
        return sprintf('%s:%s', $this->prefix, $this->base);
    }

    /**
     * @return DOMElement
     */
    public function getDomElement(): DOMElement
    {
        return $this->element;
    }

    /**
     * @return string
     */
    public function getBase(): string
    {
        return $this->base;
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @return Element[]
     */
    public function getElements(): array
    {
        $elements = [];

        $nodes = $this->getDomElement()->getElementsByTagName('element');
        for ($i = 0, $c = $nodes->length; $i < $c; $i++) {
            $node = $nodes->item($i);

            $elements[] = new Element($node);
        }

        return $elements;
    }
}
