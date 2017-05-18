<?php

namespace Dgame\Soap\Test\Object;

use Dgame\Soap\Attribute\Attribute;
use Dgame\Soap\Hydrator\Dom\AssemblableInterface;
use Dgame\Soap\XmlElement;

/**
 * Class Phone
 * @package Dgame\Soap\Test\Object
 */
final class Phone implements AssemblableInterface
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $version;

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value)
    {
        $this->version = $value;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->version;
    }

    /**
     * @return XmlElement
     */
    public function assemble(): XmlElement
    {
        $element = new XmlElement('phone', $this->version);
        $element->setAttribute(new Attribute('name', $this->name));

        return $element;
    }
}