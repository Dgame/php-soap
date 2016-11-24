<?php

namespace Dgame\Soap\Visitor;

use Dgame\Soap\Element\Element;
use Dgame\Soap\element\XmlElement;
use Dgame\Soap\Element\XmlNode;
use Dgame\Soap\PrefixableInterface;

/**
 * Class PrefixInheritVisitor
 * @package Dgame\Soap\Visitor
 */
class PrefixInheritVisitor implements ElementVisitorInterface
{
    /**
     * @var PrefixableInterface
     */
    private $prefixable;

    /**
     * PrefixInheritVisitor constructor.
     *
     * @param PrefixableInterface $prefixable
     */
    public function __construct(PrefixableInterface $prefixable)
    {
        $this->prefixable = $prefixable;
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
        $element->inheritPrefix($this->prefixable);
    }

    /**
     * @param XmlNode $node
     */
    public function visitXmlNode(XmlNode $node)
    {
        $node->inheritPrefix($this->prefixable);

        foreach ($node->getElements() as $element) {
            $element->accept($this);
        }
    }
}