<?php

namespace Dgame\Soap\Visitor;

use Dgame\Soap\Element\Element;
use Dgame\Soap\element\XmlElement;
use Dgame\Soap\Element\XmlNode;
use Dgame\Soap\PrefixableInterface;

class PrefixInheritVisitor implements ElementVisitorInterface
{
    private $prefixable;

    public function __construct(PrefixableInterface $prefixable)
    {
        $this->prefixable = $prefixable;
    }

    public function visitElement(Element $element)
    {
    }

    public function visitXmlElement(XmlElement $element)
    {
        $element->inheritPrefix($this->prefixable);
    }

    public function visitXmlNode(XmlNode $node)
    {
        $node->inheritPrefix($this->prefixable);
    }
}