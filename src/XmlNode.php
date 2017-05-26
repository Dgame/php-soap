<?php

namespace Dgame\Soap;

use Dgame\Soap\Visitor\ElementPrefixInheritanceVisitor;
use Dgame\Soap\Visitor\ElementVisitorInterface;

/**
 * Class XmlNode
 * @package Dgame\Soap
 */
class XmlNode extends XmlElement
{
    /**
     * @var Element[]
     */
    private $elements = [];

    /**
     * @param string $prefix
     */
    public function setPrefix(string $prefix)
    {
        parent::setPrefix($prefix);

        $visitor = new ElementPrefixInheritanceVisitor($this);
        foreach ($this->elements as $element) {
            $element->accept($visitor);
        }
    }

    /**
     * @param Element $element
     */
    public function appendElement(Element $element)
    {
        $this->elements[] = $element;
        $element->accept(new ElementPrefixInheritanceVisitor($this));
    }

    /**
     * @return Element[]|XmlNode[]
     */
    public function getElements(): array
    {
        return $this->elements;
    }

    /**
     * @return bool
     */
    public function hasElements(): bool
    {
        return !empty($this->elements);
    }

    /**
     * @param ElementVisitorInterface $visitor
     */
    public function accept(ElementVisitorInterface $visitor)
    {
        $visitor->visitXmlNode($this);
    }
}