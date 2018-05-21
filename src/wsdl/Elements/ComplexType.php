<?php

namespace Dgame\Soap\Wsdl\Elements;

use DOMElement;

/**
 * Class ComplexType
 * @package Dgame\Soap\Wsdl\Elements
 */
final class ComplexType
{
    /**
     * @var DOMElement
     */
    private $element;
    /**
     * @var array
     */
    private $extensions = [];

    /**
     * ComplexType constructor.
     *
     * @param DOMElement $element
     */
    public function __construct(DOMElement $element)
    {
        $this->element = $element;
    }

    /**
     * @return bool
     */
    public function isAbstract(): bool
    {
        return $this->element->hasAttribute('abstract')
               && filter_var($this->element->getAttribute('abstract'), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->element->getAttribute('name');
    }

    /**
     * @return DOMElement
     */
    public function getElement(): DOMElement
    {
        return $this->element;
    }

    /**
     * @return Extension
     */
    public function getExtension(): Extension
    {
        $extensions = $this->getExtensions();

        return reset($extensions);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasExtensionWithName(string $name): bool
    {
        return array_key_exists($name, $this->extensions);
    }

    /**
     * @param string $name
     *
     * @return Extension
     */
    public function getExtensionByName(string $name): Extension
    {
        return $this->extensions[$name];
    }

    /**
     * @return Element[]
     */
    public function getElements(): array
    {
        $nodes = $this->getElement()->getElementsByTagName('element');

        $elements = [];
        for ($i = 0, $c = $nodes->length; $i < $c; $i++) {
            $node      = $nodes->item($i);

            $elements[] = new Element($node);
        }

        return $elements;
    }

    /**
     * @return Extension[]
     */
    public function getExtensions(): array
    {
        if (!empty($this->extensions)) {
            return $this->extensions;
        }

        $nodes = $this->element->getElementsByTagName('extension');

        for ($i = 0, $c = $nodes->length; $i < $c; $i++) {
            $node      = $nodes->item($i);
            $extension = new Extension($node, $node->getAttribute('base'));

            $this->extensions[$extension->getBase()] = $extension;
        }

        return $this->extensions;
    }
}
