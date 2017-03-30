<?php

namespace Dgame\Soap\Test\Object;

use Dgame\Soap\Hydrator\Hydratable;

/**
 * Class Address
 * @package Dgame\Soap\Test\Object
 */
final class Address extends Hydratable
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
}