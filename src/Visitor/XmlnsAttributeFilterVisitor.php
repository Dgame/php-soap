<?php

namespace Dgame\Soap\Visitor;

use Dgame\Soap\Attribute\AttributeInterface;
use Dgame\Soap\Attribute\XmlAttributeInterface;
use Dgame\Soap\Attribute\XmlnsAttribute;
use Dgame\Soap\Element\XmlElementInterface;

/**
 * Class XmlnsAttributeVisitor
 * @package Dgame\Soap\Visitor
 */
final class XmlnsAttributeFilterVisitor implements AttributeVisitorInterface
{
    /**
     * @var XmlnsAttribute[]
     */
    private $attributes = [];
    /**
     * @var bool
     */
    private $hasPrefix = false;

    /**
     * XmlnsAttributeFilterVisitor constructor.
     *
     * @param XmlElementInterface $element
     */
    public function __construct(XmlElementInterface $element)
    {
        $this->hasPrefix = $element->hasPrefix();

        foreach ($element->getAttributes() as $attribute) {
            $attribute->accept($this);
        }
    }

    /**
     * @return bool
     */
    public function canSkipPrefix(): bool
    {
        return !$this->hasPrefix && $this->hasXmlnsAttributes();
    }

    /**
     * @return bool
     */
    public function hasXmlnsAttributes(): bool
    {
        return !empty($this->attributes);
    }

    /**
     * @return XmlnsAttribute[]
     */
    public function getXmlnsAttributes(): array
    {
        return $this->attributes;
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
        $this->attributes[] = $attribute;
    }
}
