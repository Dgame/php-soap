<?php

namespace Dgame\Soap;

use DOMElement;

/**
 * Class AttributeCollection
 * @package Dgame\Soap
 */
final class AttributeCollection
{
    /**
     * @var Attribute[]
     */
    private $attributes = [];

    use NamespaceTrait;

    /**
     * AttributeCollection constructor.
     *
     * @param string|null $namespace
     */
    public function __construct(string $namespace = null)
    {
        $this->namespace = $namespace;
    }

    /**
     * @return Attribute[]
     */
    public function getAttributes() : array
    {
        return $this->attributes;
    }

    /**
     * @return bool
     */
    public function hasAttributes() : bool
    {
        return !empty($this->attributes);
    }

    /**
     * @param callable $callback
     *
     * @return Attribute[]
     */
    public function getAttributeBy(callable $callback)
    {
        return array_filter($this->attributes, $callback);
    }

    /**
     * @param string $value
     */
    public function refer(string $value)
    {
        $this->attributes[] = new Attribute($this->namespace, $value);
    }

    /**
     * @param Attribute $attribute
     */
    public function appendAttribute(Attribute $attribute)
    {
        if ($this->hasNamespace()) {
            $attribute->setNamespace($this->namespace);
        }

        $this->attributes[$attribute->getIdentifier()] = $attribute;
    }

    /**
     * @param DOMElement $element
     */
    public function assembleIn(DOMElement $element)
    {
        foreach ($this->attributes as $attribute) {
            $element->setAttribute($attribute->getIdentifier(), $attribute->getValue());
        }
    }
}