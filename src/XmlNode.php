<?php

namespace Dgame\Soap;

use Dgame\Soap\Visitor\ElementVisitor;

/**
 * Class XmlNode
 * @package Dgame\Soap
 */
class XmlNode extends XmlElement implements ElementVisitor
{
    /**
     * @var Element[]
     */
    private $children = [];

    /**
     * @param Element $element
     */
    final public function appendChild(Element $element)
    {
        $element->accept($this);

        $this->children[] = $element;
    }

    /**
     * @return bool
     */
    final public function hasChildren() : bool
    {
        return !empty($this->children);
    }

    /**
     * @return Element[]
     */
    final public function getChildren() : array
    {
        return $this->children;
    }

    /**
     * @return array
     */
    public function export() : array
    {
        return [];
    }

    /**
     * @param ElementVisitor $visitor
     */
    public function accept(ElementVisitor $visitor)
    {
        $visitor->visitXmlNode($this);
    }

    /**
     * @param Element $element
     */
    public function visitElement(Element $element)
    {
    }

    /**
     * @param XmlElement $element
     */
    public function visitXmlElement(XmlElement $element)
    {
        $element->inheritPrefix($this);
    }

    /**
     * @param XmlNode $node
     */
    public function visitXmlNode(XmlNode $node)
    {
        $node->inheritPrefix($this);
    }
}
