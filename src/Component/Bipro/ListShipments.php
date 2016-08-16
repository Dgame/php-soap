<?php

namespace Dgame\Soap\Component\Bipro;

use Dgame\Soap\Node;

/**
 * Class ListShipments
 * @package Dgame\Soap\Component\Bipro
 */
class ListShipments extends Node
{
    /**
     * @var Request|null
     */
    private $request = null;

    /**
     * ListShipments constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct();

        $this->request = $request;
        $this->appendAttributes(
            [
                'xmlns' => [
                    'transfer' => 'http://www.bipro.net/namespace/transfer'
                ]
            ]
        );
    }

    /**
     * @return Request
     */
    final public function getRequest() : Request
    {
        return $this->request;
    }
}