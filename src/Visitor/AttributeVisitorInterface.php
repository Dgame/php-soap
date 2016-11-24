<?php

namespace Dgame\Soap\Visitor;

use Dgame\Soap\Attribute\Attribute;
use Dgame\Soap\Attribute\DefaultXmlnsAttribute;
use Dgame\Soap\Attribute\SoapAttribute;
use Dgame\Soap\Attribute\XmlAttribute;
use Dgame\Soap\Attribute\XmlnsAttribute;

/**
 * Interface AttributeVisitorInterface
 * @package Dgame\Soap\Visitor
 */
interface AttributeVisitorInterface
{
    /**
     * @param Attribute $attribute
     */
    public function visitAttribute(Attribute $attribute);

    /**
     * @param XmlAttribute $attribute
     */
    public function visitXmlAttribute(XmlAttribute $attribute);

    /**
     * @param XmlnsAttribute $attribute
     */
    public function visitXmlnsAttribute(XmlnsAttribute $attribute);

    /**
     * @param DefaultXmlnsAttribute $attribute
     */
    public function visitDefaultXmlnsAttribute(DefaultXmlnsAttribute $attribute);

    /**
     * @param SoapAttribute $attribute
     */
    public function visitSoapAttribute(SoapAttribute $attribute);
}