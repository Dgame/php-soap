<?php

namespace Dgame\Soap\Visitor;

use Dgame\Soap\Element\Element;
use Dgame\Soap\Element\XmlElement;
use Dgame\Soap\Element\XmlNode;

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
     * @param XmlElement $elment
     */
    public function visitXmlElement(XmlElement $elment);

    /**
     * @param XmlNode $node
     */
    public function visitXmlNode(XmlNode $node);
}