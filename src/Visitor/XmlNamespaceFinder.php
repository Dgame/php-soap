<?php

namespace Dgame\Soap\Visitor;

use Dgame\Soap\Attribute\AttributeInterface;
use Dgame\Soap\Attribute\XmlAttributeInterface;
use Dgame\Soap\Attribute\XmlnsAttribute;
use Dgame\Soap\Element\XmlElementInterface;

/**
 * Class XmlNamespaceFinder
 * @package Soap\Visitor
 */
final class XmlNamespaceFinder implements AttributeVisitorInterface
{
    /**
     * @var string
     */
    private $prefix;
    /**
     * @var XmlnsAttribute
     */
    private $attribute;

    /**
     * XmlNamespaceFinder constructor.
     *
     * @param XmlElementInterface $element
     */
    public function __construct(XmlElementInterface $element)
    {
        if ($element->hasPrefix()) {
            $this->traverse($element);
        }
    }

    /**
     * @param XmlElementInterface $element
     */
    private function traverse(XmlElementInterface $element): void
    {
        $this->prefix = $element->getPrefix();

        foreach ($element->getAttributes() as $attribute) {
            $attribute->accept($this);
            if ($this->hasNamespace()) {
                break;
            }
        }
    }

    /**
     * @return bool
     */
    public function hasNamespace(): bool
    {
        return $this->attribute !== null;
    }

    /**
     * @return XmlnsAttribute
     */
    public function getNamespace(): XmlnsAttribute
    {
        return $this->attribute;
    }

    /**
     * @param AttributeInterface $attribute
     */
    public function visitAttribute(AttributeInterface $attribute): void
    {
    }

    /**
     * @param XmlAttributeInterface $attribute
     */
    public function visitXmlAttribute(XmlAttributeInterface $attribute): void
    {
    }

    /**
     * @param XmlnsAttribute $attribute
     */
    public function visitXmlnsAttribute(XmlnsAttribute $attribute): void
    {
        if ($attribute->getName() === $this->prefix) {
            $this->attribute = $attribute;
        }
    }
}