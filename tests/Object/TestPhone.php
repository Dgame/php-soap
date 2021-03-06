<?php

namespace Dgame\Soap\Test\Object;

use Dgame\Soap\Attribute\Attribute;
use Dgame\Soap\Hydrator\AssemblableInterface;
use Dgame\Soap\XmlElement;

/**
 * Class TestPhone
 * @package Dgame\Soap\Test\Object
 */
final class TestPhone implements AssemblableInterface
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
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
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