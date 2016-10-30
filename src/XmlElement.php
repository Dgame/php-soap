<?php

namespace Dgame\Soap;

use Dgame\Soap\Visitor\ElementVisitor;

/**
 * Class XmlElement
 * @package Dgame\Soap
 */
class XmlElement extends Element
{
    /**
     * @var string
     */
    private $prefix;

    /**
     * XmlElement constructor.
     *
     * @param string|null $name
     * @param string|null $value
     * @param string|null $prefix
     */
    public function __construct(string $name = null, string $value = null, string $prefix = null)
    {
        parent::__construct($name, $value);

        $this->prefix = $prefix;
    }

    /**
     * @return string
     */
    final public function getNamespace(): string
    {
        if ($this->hasPrefix()) {
            return sprintf('%s:%s', $this->prefix, $this->getName());
        }

        return $this->getName();
    }

    /**
     * @param XmlElement $element
     */
    final public function inheritPrefix(XmlElement $element)
    {
        if (!$this->hasPrefix() && $element->hasPrefix()) {
            $this->prefix = $element->getPrefix();
        }
    }

    /**
     * @return bool
     */
    final public function hasPrefix(): bool
    {
        return !empty($this->prefix);
    }

    /**
     * @return string
     */
    final public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @param XmlnsAttribute $attribute
     */
    public function visitXmlnsAttribute(XmlnsAttribute $attribute)
    {
        if (!$this->hasPrefix() && $attribute->hasPrefix()) {
            $attribute->increaseUsage();

            $this->prefix = $attribute->getPrefix();
        }
    }

    /**
     * @param ElementVisitor $visitor
     */
    public function accept(ElementVisitor $visitor)
    {
        $visitor->visitXmlElement($this);
    }
}