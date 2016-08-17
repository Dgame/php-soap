<?php

namespace Dgame\Soap\Visitor;

use Dgame\Soap\Attribute;
use Dgame\Soap\DefaultXmlnsAttribute;
use Dgame\Soap\SoapAttribute;
use Dgame\Soap\XmlnsAttribute;

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