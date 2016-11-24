<?php

namespace Dgame\Soap\Element;

use Dgame\Soap\Visitor\ElementVisitorInterface;
use Dgame\Soap\Visitor\PrefixInheritVisitor;

class XmlNode extends XmlElement
{
    /**
     * @var Element[]
     */
    private $elements = [];

    final public function attachElement(Element $element)
    {
        $visitor = new PrefixInheritVisitor($this);
        $element->accept($visitor);

        $this->elements[] = $element;
    }

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

    public function accept(ElementVisitorInterface $visitor)
    {
        $visitor->visitXmlNode($this);
    }
}
