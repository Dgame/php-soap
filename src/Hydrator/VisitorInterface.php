<?php

namespace Dgame\Soap\Hydrator;

use Dgame\Soap\Attribute\Attribute;
use Dgame\Soap\Attribute\XmlAttribute;
use Dgame\Soap\Element;
use Dgame\Soap\XmlElement;
use Dgame\Soap\XmlNode;

/**
 * Interface VisitorInterface
 * @package Dgame\Soap\Hydrator
 */
interface VisitorInterface
{
    /**
     * @param Element $element
     */
    public function visitElement(Element $element);

    /**
     * @param XmlElement $element
     */
    public function visitXmlElement(XmlElement $element);

    /**
     * @param XmlNode $node
     */
    public function visitXmlNode(XmlNode $node);

    /**
     * @param Attribute $attribute
     */
    public function visitAttribute(Attribute $attribute);

    /**
     * @param XmlAttribute $attribute
     */
    public function visitXmlAttribute(XmlAttribute $attribute);
}