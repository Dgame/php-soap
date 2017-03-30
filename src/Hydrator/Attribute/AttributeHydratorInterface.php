<?php

namespace Dgame\Soap\Hydrator\Attribute;

use Dgame\Soap\Attribute\Attribute;
use Dgame\Soap\Attribute\XmlAttribute;

/**
 * Interface AttributeHydratorInterface
 * @package Dgame\Soap\Hydrator\Attribute
 */
interface AttributeHydratorInterface
{
    /**
     * @param Attribute $attribute
     */
    public function visitAttribute(Attribute $attribute);

    /**
     * @param XmlAttribute $attribute
     */
    public function visitXmlAttribute(XmlAttribute $attribute);
}