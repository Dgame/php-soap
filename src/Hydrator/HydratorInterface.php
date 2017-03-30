<?php

namespace Dgame\Soap\Hydrator;

use Dgame\Soap\Element;
use Dgame\Soap\XmlElement;
use Dgame\Soap\XmlNode;

/**
 * Interface HydratorInterface
 * @package Dgame\Soap\Hydrator
 */
interface HydratorInterface
{
    /**
     * @param Element $element
     */
    public function hydrateElement(Element $element);

    /**
     * @param XmlElement $element
     */
    public function hydrateXmlElement(XmlElement $element);

    /**
     * @param XmlNode $node
     */
    public function hydrateXmlNode(XmlNode $node);
}