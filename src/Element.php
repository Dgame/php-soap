<?php

namespace Dgame\Soap;

use Dgame\Soap\Visitor\NodeAppendVisitor;
use DOMDocument;
use DOMElement;

/**
 * Class Element
 * @package Dgame\Soap
 */
class Element
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
     * @param DOMDocument $document
     *
     * @return DOMElement
     */
    final public function createBy(DOMDocument $document) : DOMElement
    {
        if ($this->hasValue()) {
            return $document->createElement($this->getIdentifier(), $this->value);
        }

        return $document->createElement($this->getIdentifier());
    }

    /**
     * @param DOMElement $element
     */
    protected function beforeAssemble(DOMElement $element)
    {
    }

    /**
     * @param DOMElement $element
     * @param DOMElement $child
     */
    protected function afterAssemble(DOMElement $element, DOMElement $child)
    {
        $this->assembleAttributesIn($child);
    }

    /**
     * @param DOMElement $element
     */
    final protected function assembleAttributesIn(DOMElement $element)
    {
        foreach ($this->attributes as $attributes) {
            $attributes->assembleIn($element);
        }
    }

    /**
     * @param DOMElement $element
     */
    public function assembleIn(DOMElement $element)
    {
        $this->beforeAssemble($element);

        $child = $this->createBy($element->ownerDocument);
        $element->appendChild($child);

        $this->afterAssemble($element, $child);
    }

    /**
     * @param NodeAppendVisitor $visitor
     */
    public function accept(NodeAppendVisitor $visitor)
    {
        $visitor->visitElement($this);
    }
}