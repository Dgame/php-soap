<?php

namespace Dgame\Soap\Visitor;

use Dgame\Soap\Element\ElementInterface;
use Dgame\Soap\Element\XmlElementInterface;
use Dgame\Soap\Element\XmlNodeInterface;

/**
 * Class ElementPrefixInheritance
 * @package Soap\Visitor
 */
final class ElementPrefixInheritance implements ElementVisitorInterface
{
    /**
     * @var string
     */
    private $prefix;

    /**
     * @param ElementInterface $element
     */
    public function visitElement(ElementInterface $element): void
    {
    }

    /**
     * @param XmlElementInterface $element
     */
    public function visitXmlElement(XmlElementInterface $element): void
    {
        if ($element->hasPrefix()) {
            return;
        }

        $visitor = new XmlnsAttributeFilterVisitor($element);
        if ($visitor->canSkipPrefix()) {
            return;
        }

        $element->setPrefix($this->prefix);
    }

    /**
     * @param XmlNodeInterface $node
     */
    public function visitXmlNode(XmlNodeInterface $node): void
    {
        if ($node->hasPrefix()) {
            $this->prefix = $node->getPrefix();
        }

        $visitor = new XmlnsAttributeFilterVisitor($node);
        if ($visitor->canSkipPrefix()) {
            return;
        }

        $this->visitXmlElement($node);

        $self         = new self();
        $self->prefix = $this->prefix;

        foreach ($node->getElements() as $element) {
            $element->accept($self);
        }
    }
}
