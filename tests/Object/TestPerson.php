<?php

namespace Dgame\Soap\Test\Object;

use DateTime;
use Dgame\Soap\Attribute\Attribute;
use Dgame\Soap\Hydrator\AssemblableInterface;
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
     * @var DateTime
     */
    private $birthday;
    /**
     * @var TestHobby
     */
    public $hobby;

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param TestCar $car
     */
    public function setTestCar(TestCar $car): void
    {
        $this->car = $car;
    }

    /**
     * @param TestPhone $phone
     */
    public function setTestPhone(TestPhone $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @param string $birthplace
     */
    public function setBirthPlace(string $birthplace): void
    {
        $this->birthplace = $birthplace;
    }

    /**
     * @param TestAddress $address
     */
    public function setTestAddress(TestAddress $address): void
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
     * @return DateTime
     */
    public function getBirthday(): DateTime
    {
        return $this->birthday;
    }

    /**
     * @param string $birthday
     */
    public function setBirthday(string $birthday): void
    {
        $this->birthday = new DateTime($birthday);
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