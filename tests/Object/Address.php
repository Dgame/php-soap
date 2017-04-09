<?php

namespace Dgame\Soap\Test\Object;

use Dgame\Soap\Element;
use Dgame\Soap\Hydrator\Dom\AssemblableInterface;
use Dgame\Soap\XmlNode;

/**
 * Class Address
 * @package Dgame\Soap\Test\Object
 */
final class Address implements AssemblableInterface
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
     * @return Element
     */
    public function assemble(): Element
    {
        $node = new XmlNode('address');
        $node->appendChild(new Element('street', $this->street));
        $node->appendChild(new Element('plz', $this->plz));

        return $node;
    }
}