<?php

namespace Dgame\Soap\Hydrator;

use Dgame\Soap\Attribute\Attribute;
use Dgame\Soap\Element;

/**
 * Class Hydratable
 * @package Dgame\Soap\Hydrator
 */
class Hydratable implements HydratableInterface
{
    /**
     * @var array
     */
    private $attributes = [];

    /**
     * @param Attribute $attribute
     */
    final public function assignAttribute(Attribute $attribute)
    {
        if ($attribute->hasValue()) {
            $this->assign($attribute->getName(), $attribute->getValue());
        }
    }

    /**
     * @param Element $element
     */
    final public function assignElement(Element $element)
    {
        if ($element->hasValue()) {
            $this->assign($element->getName(), $element->getValue());
        }
    }

    /**
     * @param string $name
     * @param string $value
     */
    final public function assign(string $name, string $value)
    {
        if (!Method::of($name, $this)->assign($value)) {
            $this->setAttribute($name, $value);
        }
    }

    /**
     * @param HydratableInterface $hydrat
     *
     * @return bool
     */
    final public function assignHydratable(HydratableInterface $hydrat): bool
    {
        return Method::of($hydrat->getClassName(), $this)->assign($hydrat);
    }

    /**
     * @return string
     */
    final public function getClassName(): string
    {
        static $name = null;
        if ($name === null) {
            $name = string(static::class)->lastSegment('\\');
        }

        return $name;
    }

    /**
     * @param string $name
     * @param        $value
     */
    final public function setAttribute(string $name, $value)
    {
        $this->attributes[$name] = $value;
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    final public function getAttribute(string $name)
    {
        return $this->attributes[$name];
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    final public function hasAttribute(string $name): bool
    {
        return array_key_exists($name, $this->attributes);
    }

    /**
     * @return array
     */
    final public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     */
    final public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }
}