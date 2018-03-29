<?php

namespace Dgame\Soap\Element;

/**
 * Class XmlDocument
 * @package Soap\Element
 */
class XmlDocument extends XmlNode
{
    /**
     * XmlDocument constructor.
     */
    public function __construct()
    {
        parent::__construct('#document');

        $this->appendElement(new XmlSpecification());
    }
}