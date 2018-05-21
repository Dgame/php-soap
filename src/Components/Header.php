<?php

namespace Dgame\Soap\Components;

use Dgame\Soap\Element\XmlNode;

/**
 * Class Header
 * @package Dgame\Soap\Components
 */
final class Header extends XmlNode
{
    /**
     * Header constructor.
     */
    public function __construct()
    {
        parent::__construct('Header');
    }
}
