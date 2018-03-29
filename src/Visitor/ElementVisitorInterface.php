<?php

namespace Dgame\Soap\Visitor;

use Dgame\Soap\Element\ElementInterface;
use Dgame\Soap\Element\XmlElementInterface;
use Dgame\Soap\Element\XmlNodeInterface;

/**
 * Interface ElementVisitorInterface
 * @package Soap\Visitor
 */
interface ElementVisitorInterface
{
    /**
     * @param ElementInterface $element
     */
    public function visitElement(ElementInterface $element): void;

    /**
     * @param XmlElementInterface $element
     */
    public function visitXmlElement(XmlElementInterface $element): void;

    /**
     * @param XmlNodeInterface $node
     */
    public function visitXmlNode(XmlNodeInterface $node): void;
}