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

    public function setPrefix(string $prefix): void
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
    final public function appendElement(Element $element): void
    {
        $this->elements[] = $element;
        $element->accept(new ElementPrefixInheritanceVisitor($this));
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
    public function accept(ElementVisitorInterface $visitor): void
    {
        $visitor->visitXmlNode($this);
    }
}