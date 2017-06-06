<?php

namespace Dgame\Soap\Test\Object;

use Dgame\Soap\Hydrator\AssemblableInterface;
use Dgame\Soap\XmlElement;
use Dgame\Soap\XmlNode;

/**
 * Class TestAddress
 * @package Dgame\Soap\Test\Object
 */
final class TestAddress implements AssemblableInterface
{
    /**
     * @var string
     */
    private $street;
    /**
     * @var int
     */
    private $plz;

    /**
     * @param string $street
     */
    public function setStreet(string $street)
    {
        $this->street = $street;
    }

    /**
     * @param int $plz
     */
    public function setPlz(int $plz)
    {
        $this->plz = $plz;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @return int
     */
    public function getPlz(): int
    {
        return $this->plz;
    }

    /**
     * @return XmlElement
     */
    public function assemble(): XmlElement
    {
        $node = new XmlNode('address');
        $node->appendElement(new XmlElement('street', $this->street));
        $node->appendElement(new XmlElement('plz', $this->plz));

        return $node;
    }
}