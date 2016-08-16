<?php

namespace Dgame\Soap;

use Dgame\Soap\Visitor\VisitableInterface;
use Dgame\Soap\Visitor\VisitorInterface;

/**
 * Class Element
 * @package Dgame\Soap
 */
class Element implements VisitableInterface
{
    /**
     * @var string
     */
    private $name = '';
    /**
     * @var string
     */
    private $value = '';
    /**
     * @var AttributeCollection[]
     */
    private $attributes = [];

    use NamespaceTrait;

    /**
     * Element constructor.
     *
     * @param string|null $name
     * @param string|null $value
     * @param string|null $namespace
     */
    public function __construct(string $name = null, string $value = null, string $namespace = null)
    {
        $this->name  = $name ?? $this->getClassName();
        $this->value = (string) $value;

        $this->setNamespace((string) $namespace);
    }

    /**
     * @param array $change
     */
    public function onNamespaceChange(array $change)
    {
        foreach ($this->attributes as $collection) {
            $collection->onNamespaceChange($change);
        }
    }

    /**
     * @return string
     */
    final public function getClassName() : string
    {
        return substr(strrchr(static::class, '\\'), 1);
    }

    /**
     * @param AttributeCollection $collection
     */
    final public function appendAttributeCollection(AttributeCollection $collection)
    {
        if (!$this->hasNamespace() && $collection->hasNamespace()) {
            $attrs = $collection->getAttributeBy(function(Attribute $attribute) {
                return $attribute->hasNamespace();
            });

            if (!empty($attrs)) {
                $this->setNamespace(reset($attrs)->getName());
            }
        }

        $this->attributes[] = $collection;
    }

    /**
     * @param array $attributes
     */
    final public function appendAttributes(array $attributes)
    {
        foreach ($attributes as $key => $attrs) {
            $collection = new AttributeCollection(is_string($key) ? $key : null);
            foreach ($attrs as $name => $value) {
                if (is_string($name)) {
                    $collection->appendAttribute(new Attribute($name, $value));
                } else {
                    $collection->refer($value);
                }
            }

            $this->appendAttributeCollection($collection);
        }
    }

    /**
     * @return AttributeCollection[]
     */
    final public function getAttributes() : array
    {
        return $this->attributes;
    }

    /**
     * @param string $value
     */
    final public function setValue(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    final public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    final public function hasValue() : bool
    {
        return !empty($this->value);
    }

    /**
     * @return string
     */
    final public function getValue() : string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    final public function getIdentifier() : string
    {
        if ($this->hasNamespace()) {
            return sprintf('%s:%s', $this->getNamespace(), $this->name);
        }

        return $this->name;
    }

    /**
     * @param VisitorInterface $visitor
     */
    public function accept(VisitorInterface $visitor)
    {
        $visitor->visitElement($this);
    }
}