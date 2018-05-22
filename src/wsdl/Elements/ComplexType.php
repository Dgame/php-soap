<?php

namespace Dgame\Soap\Wsdl\Elements;

use function Dgame\Ensurance\enforce;
use DOMElement;
use function Dgame\Ensurance\ensure;

/**
 * Class ComplexType
 * @package Dgame\Soap\Wsdl\Elements
 */
final class ComplexType extends SimpleType
{
    /**
     * @var Extension[]
     */
    private $extensions = [];

    /**
     * ComplexType constructor.
     *
     * @param DOMElement $element
     */
    public function __construct(DOMElement $element)
    {
        parent::__construct($element);
    }

    /**
     * @param ComplexType|null $complex
     *
     * @return bool
     */
    public function isComplexType(ComplexType &$complex = null): bool
    {
        $complex = $this;

        return true;
    }

    /**
     * @return bool
     */
    public function isAbstract(): bool
    {
        return $this->hasAttribute('abstract')
               && filter_var($this->getAttribute('abstract'), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return bool
     */
    public function hasExtensions(): bool
    {
        $extensions = $this->getExtensions();

        return !empty($extensions);
    }

    /**
     * @return Extension
     */
    public function getFirstExtension(): Extension
    {
        $extensions = $this->getExtensions();
        ensure($extensions)->isNotEmpty()->orThrow('No Extensions found');
        ensure($extensions)->isArray()->hasLengthOf(1)->orThrow('Found multiple Extensions');

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
     * @return Extension[]
     */
    public function getExtensions(): array
    {
        if (!empty($this->extensions)) {
            return $this->extensions;
        }

        $nodes = $this->getDomElement()->getElementsByTagName('extension');
        for ($i = 0, $c = $nodes->length; $i < $c; $i++) {
            $node      = $nodes->item($i);
            $extension = new Extension($node, $node->getAttribute('base'));

            $this->extensions[$extension->getBase()] = $extension;
        }

        return $this->extensions;
    }

    /**
     * @param string $name
     *
     * @return Element
     */
    public function getElementByName(string $name): Element
    {
        $elements = $this->getElementsByName($name);

        enforce(count($elements) !== 0)->orThrow('There are no nodes with name %s', $name);
        enforce(count($elements) === 1)->orThrow('There are multiple nodes with name %s', $name);

        return array_pop($elements);
    }

    /**
     * @param string $name
     *
     * @return Element[]
     */
    public function getElementsByName(string $name): array
    {
        $elements = [];

        $nodes = $this->getDomElement()->getElementsByTagName($name);
        for ($i = 0, $c = $nodes->length; $i < $c; $i++) {
            $node = $nodes->item($i);

            $elements[$node->getAttribute('name')] = new self($node);
        }

        return $elements;
    }

    /**
     * @return Element[]
     */
    public function getElements(): array
    {
        $nodes = $this->getDomElement()->getElementsByTagName('element');

        $elements = [];
        for ($i = 0, $c = $nodes->length; $i < $c; $i++) {
            $node = $nodes->item($i);

            $elements[] = new Element($node);
        }

        return $elements;
    }
}
