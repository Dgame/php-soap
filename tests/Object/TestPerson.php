<?php

namespace Dgame\Soap\Test\Object;

use Dgame\Soap\Attribute\Attribute;
use Dgame\Soap\Hydrator\Dom\AssemblableInterface;
use Dgame\Soap\XmlElement;
use Dgame\Soap\XmlNode;

/**
 * Class TestPerson
 * @package Dgame\Soap\Test\Object
 */
final class TestPerson implements AssemblableInterface
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var TestCar
     */
    private $car;
    /**
     * @var TestPhone
     */
    private $phone;
    /**
     * @var string
     */
    private $birthplace;
    /**
     * @var TestAddress
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
     * @param TestCar $car
     */
    public function setTestCar(TestCar $car)
    {
        $this->car = $car;
    }

    /**
     * @param TestPhone $phone
     */
    public function setTestPhone(TestPhone $phone)
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
     * @param TestAddress $address
     */
    public function setTestAddress(TestAddress $address)
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
     * @return TestCar
     */
    public function getCar(): TestCar
    {
        return $this->car;
    }

    /**
     * @return TestPhone
     */
    public function getPhone(): TestPhone
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
     * @return TestAddress
     */
    public function getAddress(): TestAddress
    {
        return $this->address;
    }

    /**
     * @return XmlElement
     */
    public function assemble(): XmlElement
    {
        $node = new XmlNode('person');
        $node->setAttribute(new Attribute('name', $this->name));
        $node->appendElement($this->car->assemble());
        $node->appendElement($this->phone->assemble());
        $node->appendElement(new XmlElement('birth-place', $this->birthplace));
        $node->appendElement($this->address->assemble());

        return $node;
    }
}