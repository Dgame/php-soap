<?php

namespace Dgame\Soap\Component\Bipro;

use Dgame\Soap\XmlNode;
use Dgame\Soap\XmlnsAttribute;

/**
 * Class AckShipment
 * @package Dgame\Soap\Component\Bipro
 */
class AckShipment extends XmlNode
{
    /**
     * @var Request
     */
    private $request;

    /**
     * GetShipment constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct();

        $this->request = $request;

        $this->setAttribute(new XmlnsAttribute('transfer', 'http://www.bipro.net/namespace/transfer'));
        $this->setAttribute(new XmlnsAttribute('komposit', '"http://www.bipro.net/namespace/komposit'));
    }

    /**
     * @return Request
     */
    public function getRequest() : Request
    {
        return $this->request;
    }

    /**
     * @return array
     */
    public function export() : array
    {
        return ['request'];
    }
}