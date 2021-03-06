<?php

namespace Dgame\Soap\Visitor;

use Dgame\Soap\Element;
use Dgame\Soap\XmlElement;
use Dgame\Soap\XmlNode;

/**
 * Class ElementPrefixInheritanceVisitor
 * @package Dgame\Soap\Visitor
 */
final class ElementPrefixInheritanceVisitor implements ElementVisitorInterface
{
    /**
     * @var XmlNode
     */
    private $node;

    /**
     * PrefixInheritanceVisitor constructor.
     *
     * @param XmlNode $node
     */
    public function __construct(XmlNode $node)
    {
        $this->node = $node;
    }

    /**
     * @param Element $element
     */
    public function visitElement(Element $element): void
    {
    }

    /**
     * @param XmlElement $element
     */
    public function visitXmlElement(XmlElement $element): void
    {
        $this->inheritPrefix($element);
    }

    /**
     * @param XmlNode $node
     */
    public function visitXmlNode(XmlNode $node): void
    {
        $this->inheritPrefix($node);
    }

    /**
     * @param XmlElement $element
     */
    private function inheritPrefix(XmlElement $element): void
    {
        if (!$element->hasPrefix() && $this->node->hasPrefix()) {
            $element->setPrefix($this->node->getPrefix());
        }
    }
}