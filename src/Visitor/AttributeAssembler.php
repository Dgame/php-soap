<?php

namespace Dgame\Soap\Visitor;

use Dgame\Soap\Attribute\Attribute;
use Dgame\Soap\Attribute\DefaultXmlnsAttribute;
use Dgame\Soap\Attribute\SoapAttribute;
use Dgame\Soap\Attribute\XmlAttribute;
use Dgame\Soap\Attribute\XmlnsAttribute;
use DOMElement;

/**
 * Class AttributeAssembler
 * @package Dgame\Soap\Visitor
 */
final class AttributeAssembler implements AttributeVisitorInterface
{
    /**
     * @var DOMElement
     */
    private $element;

    /**
     * AttributeAssembler constructor.
     *
     * @param DOMElement $element
     */
    public function __construct(DOMElement $element)
    {
        $this->element = $element;
    }

    /**
     * @param Attribute $attribute
     */
    public function visitAttribute(Attribute $attribute)
    {
        $this->element->setAttribute($attribute->getName(), $attribute->getValue());
    }

    /**
     * @param XmlAttribute $attribute
     */
    public function visitXmlAttribute(XmlAttribute $attribute)
    {
        $this->element->setAttribute($attribute->getNamespace(), $attribute->getValue());
    }

    /**
     * @param XmlnsAttribute $attribute
     */
    public function visitXmlnsAttribute(XmlnsAttribute $attribute)
    {
        $this->element->setAttribute($attribute->getNamespace(), $attribute->getValue());
    }

    /**
     * @param DefaultXmlnsAttribute $attribute
     */
    public function visitDefaultXmlnsAttribute(DefaultXmlnsAttribute $attribute)
    {
        $this->element->setAttribute($attribute->getName(), $attribute->getValue());
    }

    /**
     * @param SoapAttribute $attribute
     */
    public function visitSoapAttribute(SoapAttribute $attribute)
    {
        $this->element->setAttribute($attribute->getNamespace(), $attribute->getValue());
    }
}
