<?php

namespace Dgame\Soap\Visitor;

use Dgame\Soap\Element;
use Dgame\Soap\XmlElement;
use Dgame\Soap\XmlNode;

/**
 * Interface ElementVisitorInterface
 * @package Dgame\Soap\Visitor
 */
interface ElementVisitorInterface
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
}