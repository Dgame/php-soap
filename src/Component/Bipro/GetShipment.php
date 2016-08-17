<?php

namespace Dgame\Soap\Component\Bipro;

use Dgame\Soap\Node;
use Dgame\Soap\XmlnsAttribute;

/**
 * Class GetShipment
 * @package Dgame\Soap\Component\Bipro
 */
class GetShipment extends Node
{
    /**
     * @var Request|null
     */
    private $request = null;

    /**
     * GetShipment constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct();

        $this->request = $request;

        $this->appendAttribute(new XmlnsAttribute('transfer', 'http://www.bipro.net/namespace/transfer'));
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
    public function getPropertyExport() : array
    {
        return ['request'];
    }
}