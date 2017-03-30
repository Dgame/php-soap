<?php

namespace Dgame\Soap;

use Dgame\Soap\Hydrator\HydratorInterface;

/**
 * Class XmlNode
 * @package Dgame\Soap
 */
final class XmlNode extends XmlElement
{
    /**
     * @var Element[]
     */
    private $children = [];

    /**
     * @param Element $element
     */
    public function appendChild(Element $element)
    {
        $this->children[] = $element;
    }

    /**
     * @return Element[]|XmlNode[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @return bool
     */
    public function hasChildren(): bool
    {
        return !empty($this->children);
    }

    /**
     * @param HydratorInterface $hydrator
     */
    public function hydration(HydratorInterface $hydrator)
    {
        $hydrator->hydrateXmlNode($this);
    }
}