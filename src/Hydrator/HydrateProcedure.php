<?php

namespace Dgame\Soap\Hydrator;

use Dgame\Soap\Attribute\Attribute;
use Dgame\Soap\Attribute\XmlAttribute;
use Dgame\Soap\Element;
use Dgame\Soap\XmlElement;
use Dgame\Soap\XmlNode;
use function Dgame\Conditional\debug;

/**
 * Class HydrateProcedure
 * @package Dgame\Soap\Hydrator
 */
final class HydrateProcedure implements VisitorInterface
{
    const DEBUG_LABEL = 'Debug_Soap_Hydrator';

    /**
     * @var string
     */
    private $tab;
    /**
     * @var HydratableInterface
     */
    private $hydratable;
    /**
     * @var ClassMapper
     */
    private $mapper;

    /**
     * Hydrat constructor.
     *
     * @param ClassMapper $mapper
     * @param string|null $tab
     */
    public function __construct(ClassMapper $mapper, string $tab = null)
    {
        $this->mapper = $mapper;
        $this->tab    = "\t" . $tab;
    }

    /**
     * @return HydratableInterface
     */
    public function getHydratable(): HydratableInterface
    {
        return $this->hydratable;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->hydratable !== null;
    }

    /**
     * @param Element $element
     */
    public function visitElement(Element $element)
    {
        debug(self::DEBUG_LABEL)->output($this->tab . ' - Element: ' . $element->getName());

        $this->visit($element);
    }

    /**
     * @param XmlElement $element
     */
    public function visitXmlElement(XmlElement $element)
    {
        debug(self::DEBUG_LABEL)->output($this->tab . ' - XmlElement: ' . $element->getName());

        $this->visit($element);
    }

    /**
     * @param XmlNode $node
     */
    public function visitXmlNode(XmlNode $node)
    {
        debug(self::DEBUG_LABEL)->output($this->tab . ' - XmlNode: ' . $node->getName());

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
            $this->hydratable->assign($attribute);
        }
    }

    /**
     * @param Element $element
     */
    private function visit(Element $element)
    {
        $this->hydratable = $this->mapper->getInstanceOf($element->getName());
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
            $this->hydratable->assignValue('value', $element->getValue());
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
        $hydrator = new self($this->mapper, $this->tab);
        $element->accept($hydrator);

        if ($this->isValid() && $hydrator->isValid()) {
            $this->hydratable->append($hydrator->getHydratable());
        } elseif ($this->isValid()) {
            $this->hydratable->assign($element);
        } elseif ($hydrator->isValid()) {
            $this->hydratable = $hydrator->getHydratable();
        }
    }
}