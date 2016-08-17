<?php

namespace Dgame\Soap;

/**
 * Class DefaultXmlnsAttribute
 * @package Dgame\Soap
 */
class DefaultXmlnsAttribute extends XmlnsAttribute
{
    /**
     * DefaultXmlnsAttribute constructor.
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        parent::__construct('', $value);
    }
}