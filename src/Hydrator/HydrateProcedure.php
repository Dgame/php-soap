<?php

namespace Dgame\Soap\Hydrator;

use Dgame\Soap\Element;
use Dgame\Soap\XmlElement;
use Dgame\Soap\XmlNode;
use function Dgame\Conditional\debug;

/**
 * Class HydrateProcedure
 * @package Dgame\Soap\Hydrator
 */
final class HydrateProcedure implements HydratorInterface
{
    const DEBUG_LABEL = 'Debug_Soap_Hydrat';

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
    public function hydrateElement(Element $element)
    {
        debug(self::DEBUG_LABEL)->output($this->tab . ' - Element: ' . $element->getName());

        $this->hydrate($element);
    }

    /**
     * @param XmlElement $element
     */
    public function hydrateXmlElement(XmlElement $element)
    {
        debug(self::DEBUG_LABEL)->output($this->tab . ' - XmlElement: ' . $element->getName());

        $this->hydrate($element);
    }

    /**
     * @param XmlNode $node
     */
    public function hydrateXmlNode(XmlNode $node)
    {
        debug(self::DEBUG_LABEL)->output($this->tab . ' - XmlNode: ' . $node->getName());

        $this->hydrate($node);
        $this->hydrateChildrenOf($node);
    }

    /**
     * @param Element $element
     */
    private function hydrate(Element $element)
    {
        $this->hydratable = $this->mapper->getInstanceOf($element->getName());
        if ($this->isValid()) {
            $this->hydrateAttributesOf($element);
        }
    }

    /**
     * @param Element $element
     */
    private function hydrateAttributesOf(Element $element)
    {
        foreach ($element->getAttributes() as $attribute) {
            if ($attribute->hasValue()) {
                $this->hydratable->assignAttribute($attribute);
            }
        }

        if ($element->hasValue()) {
            $this->hydratable->assign('value', $element->getValue());
        }
    }

    /**
     * @param XmlNode $node
     */
    private function hydrateChildrenOf(XmlNode $node)
    {
        foreach ($node->getChildren() as $child) {
            $this->hydrateChild($child);
        }
    }

    /**
     * @param Element $element
     */
    private function hydrateChild(Element $element)
    {
        $hydrat = new self($this->mapper, $this->tab);
        $element->hydration($hydrat);

        if ($this->isValid() && $hydrat->isValid()) {
            $this->hydratable->assignHydratable($hydrat->getHydratable());
        } else if ($this->isValid()) {
            $this->hydratable->assignElement($element);
        } else if ($hydrat->isValid()) {
            $this->hydratable = $hydrat->getHydratable();
        }
    }
}