<?php

namespace Dgame\Soap\Element;

use Dgame\Soap\Visitor\ElementVisitorInterface;
use Dgame\Soap\Visitor\PrefixInheritVisitor;

/**
 * Class XmlNode
 * @package Dgame\Soap\Element
 */
class XmlNode extends XmlElement
{
    /**
     * @var Element[]
     */
    private $elements = [];

    /**
     * @param Element $element
     */
    final public function attachElement(Element $element)
    {
        $visitor = new PrefixInheritVisitor($this);
        $element->accept($visitor);

        $this->elements[] = $element;
    }

    /**
     * @return bool
     */
    final public function hasElements(): bool
    {
        return !empty($this->elements);
    }

    /**
     * @return Element[]
     */
    final public function getElements(): array
    {
        return $this->elements;
    }

    /**
     * @param ElementVisitorInterface $visitor
     */
    public function accept(ElementVisitorInterface $visitor)
    {
        $visitor->visitXmlNode($this);
    }
}
