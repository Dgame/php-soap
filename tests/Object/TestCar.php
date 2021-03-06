<?php

namespace Dgame\Soap\Test\Object;

use Dgame\Soap\Attribute\Attribute;
use Dgame\Soap\Hydrator\AssemblableInterface;
use Dgame\Soap\XmlElement;

/**
 * Class TestCar
 * @package Dgame\Soap\Test\Object
 */
final class TestCar implements AssemblableInterface
{
    /**
     * @var string
     */
    private $marke;
    /**
     * @var string
     */
    public $kennung;

    /**
     * @param string $marke
     */
    public function setMarke(string $marke): void
    {
        $this->marke = $marke;
    }

    /**
     * @return string
     */
    public function getMarke(): string
    {
        return $this->marke;
    }

    /**
     * @return XmlElement
     */
    public function assemble(): XmlElement
    {
        $element = new XmlElement('car');
        $element->setAttribute(new Attribute('marke', $this->marke));
        $element->setAttribute(new Attribute('kennung', $this->kennung));

        return $element;
    }
}