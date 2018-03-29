<?php

namespace Dgame\Soap\Visitor;

use Dgame\Soap\Element\ElementInterface;
use Dgame\Soap\Element\XmlElementInterface;
use Dgame\Soap\Element\XmlNodeInterface;

/**
 * Class AttributeElementPrefixInheritance
 * @package Soap\Visitor
 */
final class AttributeElementPrefixInheritance implements ElementVisitorInterface
{
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

        if (!$element->hasAttributes()) {
            return;
        }

        $attributes = $element->getAttributes();
        $attribute  = array_shift($attributes);
        $element->setPrefix($attribute->getName());
    }

    /**
     * @param XmlNodeInterface $node
     */
    public function visitXmlNode(XmlNodeInterface $node): void
    {
        $this->visitXmlElement($node);
        foreach ($node->getElements() as $element) {
            $element->accept($this);
        }
    }
}