<?php

namespace Dgame\Soap\Hydrator;

use Dgame\Soap\Attribute\Attribute;
use Dgame\Soap\Attribute\XmlAttribute;
use Dgame\Soap\Element;
use Dgame\Soap\Visitor\AttributeVisitorInterface;
use Dgame\Soap\Visitor\ElementVisitorInterface;
use Dgame\Soap\XmlElement;
use Dgame\Soap\XmlNode;

/**
 * Class HydrateProcedure
 * @package Dgame\Soap\Hydrator
 */
final class HydrateProcedure implements ElementVisitorInterface, AttributeVisitorInterface
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
     * @var array
     */
    private $warnings = [];

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
     * @return array
     */
    public function getWarnings(): array
    {
        return $this->warnings;
    }

    /**
     * @return bool
     */
    public function hasWarnings(): bool
    {
        return !empty($this->warnings);
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
        } else {
            $this->warnings[] = 'Class not found: ' . $element->getName();
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
        foreach ($node->getElements() as $child) {
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
        } elseif (!$this->skipTo($procedure)) {
            $this->warnings[] = 'Could not hydrate: ' . $element->getName();
        }
    }

    /**
     * @param HydrateProcedure $procedure
     * @param Element          $element
     */
    private function appendOrAssign(self $procedure, Element $element)
    {
        if ($procedure->isValid()) {
            $this->append($procedure->getHydrate());
        } else {
            $this->assign($element);
        }
    }

    /**
     * @param HydrateProcedure $procedure
     *
     * @return bool
     */
    private function skipTo(self $procedure): bool
    {
        if (!$procedure->isValid()) {
            return false;
        }

        $this->hydrate = $procedure->getHydrate();

        return true;
    }

    /**
     * @param Hydrate $hydrate
     */
    private function append(Hydrate $hydrate)
    {
        if (!$this->hydrate->append($hydrate)) {
            $this->warnings[] = 'Could not append: ' . $hydrate->getName();
        }
    }

    /**
     * @param Element $element
     */
    private function assign(Element $element)
    {
        if (!$this->hydrate->assign($element)) {
            $this->warnings[] = 'Could not assign Element: ' . $element->getName();
        }
    }
}