<?php

namespace Dgame\Soap\Hydrator;

use Dgame\Soap\Attribute\Attribute;
use Dgame\Soap\Attribute\XmlAttribute;
use Dgame\Soap\Element;
use Dgame\Soap\XmlElement;
use Dgame\Soap\XmlNode;

/**
 * Class HydrateProcedure
 * @package Dgame\Soap\Hydrator
 */
final class HydrateProcedure implements VisitorInterface
{
    /**
     * @var Hydrate
     */
    private $hydrate;
    /**
     * @var ClassMapper
     */
    private $mapper;

    /**
     * Hydrat constructor.
     *
     * @param ClassMapper $mapper
     */
    public function __construct(ClassMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @return Hydrate
     */
    public function getHydrate(): Hydrate
    {
        return $this->hydrate;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->hydrate !== null;
    }

    /**
     * @param Element $element
     */
    public function visitElement(Element $element)
    {
        $this->visit($element);
    }

    /**
     * @param XmlElement $element
     */
    public function visitXmlElement(XmlElement $element)
    {
        $this->visit($element);
    }

    /**
     * @param XmlNode $node
     */
    public function visitXmlNode(XmlNode $node)
    {
        $this->visit($node);
        $this->visitChildrenOf($node);
    }

    /**
     * @param Attribute $attribute
     */
    public function visitAttribute(Attribute $attribute)
    {
        $this->assignAttribute($attribute);
    }

    /**
     * @param XmlAttribute $attribute
     */
    public function visitXmlAttribute(XmlAttribute $attribute)
    {
        $this->assignAttribute($attribute);
    }

    /**
     * @param Attribute $attribute
     */
    private function assignAttribute(Attribute $attribute)
    {
        if ($attribute->hasValue()) {
            $this->hydrate->assign($attribute);
        }
    }

    /**
     * @param Element $element
     */
    private function visit(Element $element)
    {
        $this->hydrate = $this->mapper->new($element->getName());
        if ($this->isValid()) {
            $this->visitAttributesOf($element);
        }
    }

    /**
     * @param Element $element
     */
    private function visitAttributesOf(Element $element)
    {
        foreach ($element->getAttributes() as $attribute) {
            $attribute->accept($this);
        }

        if ($element->hasValue()) {
            $this->hydrate->setValue('value', $element->getValue());
        }
    }

    /**
     * @param XmlNode $node
     */
    private function visitChildrenOf(XmlNode $node)
    {
        foreach ($node->getChildren() as $child) {
            $this->visitChild($child);
        }
    }

    /**
     * @param Element $element
     */
    private function visitChild(Element $element)
    {
        $procedure = new self($this->mapper);
        $element->accept($procedure);

        if ($this->isValid()) {
            $this->appendOrAssign($procedure, $element);
        } else {
            $this->skipTo($procedure);
        }
    }

    /**
     * @param HydrateProcedure $procedure
     * @param Element          $element
     */
    private function appendOrAssign(self $procedure, Element $element)
    {
        if ($procedure->isValid()) {
            $this->hydrate->append($procedure->getHydrate());
        } else {
            $this->hydrate->assign($element);
        }
    }

    /**
     * @param HydrateProcedure $procedure
     */
    private function skipTo(self $procedure)
    {
        if ($procedure->isValid()) {
            $this->hydrate = $procedure->getHydrate();
        }
    }
}