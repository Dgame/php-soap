<?php

namespace Dgame\Soap\Test\Object;

use Dgame\Soap\Hydrator\Hydratable;

/**
 * Class Stammdaten
 * @package Dgame\Soap\Test\Object
 */
final class Stammdaten extends Hydratable
{
    /**
     * @var string
     */
    public $Name;
    /**
     * @var string
     */
    public $Vorname;
}