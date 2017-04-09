<?php

namespace Dgame\Soap\Test\Object;

use Dgame\Soap\Attribute\Attribute;
use Dgame\Soap\Element;
use Dgame\Soap\Hydrator\Dom\AssemblableInterface;
use Dgame\Soap\XmlNode;

/**
 * Class Person
 * @package Dgame\Soap\Test\Object
 */
final class Person implements AssemblableInterface
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var Car
     */
    private $car;
    /**
     * @var Phone
     */
    private $phone;
    /**
     * @var string
     */
    private $birthplace;
    /**
     * @var Address
     */
    private $address;

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param Car $car
     */
    public function setCar(Car $car)
    {
        $this->car = $car;
    }

    /**
     * @param Phone $phone
     */
    public function setPhone(Phone $phone)
    {
        $this->phone = $phone;
    }

    /**
     * @param string $birthplace
     */
    public function setBirthPlace(string $birthplace)
    {
        $this->birthplace = $birthplace;
    }

    /**
     * @param Address $address
     */
    public function setAddress(Address $address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Car
     */
    public function getCar(): Car
    {
        return $this->car;
    }

    /**
     * @return Phone
     */
    public function getPhone(): Phone
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function getBirthPlace(): string
    {
        return $this->birthplace;
    }

    /**
     * @return Address
     */
    public function getAddress(): Address
    {
        return $this->address;
    }

    /**
     * @return Element
     */
    public function assemble(): Element
    {
        $node = new XmlNode('person');
        $node->setAttribute(new Attribute('name', $this->name));
        $node->appendChild($this->car->assemble());
        $node->appendChild($this->phone->assemble());
        $node->appendChild(new Element('birth-place', $this->birthplace));
        $node->appendChild($this->address->assemble());

        return $node;
    }
}