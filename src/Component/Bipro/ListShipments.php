<?php

namespace Dgame\Soap\Component\Bipro;

use Dgame\Soap\Attribute\XmlnsAttribute;
use Dgame\Soap\Component\AbstractNode;

/**
 * Class ListShipments
 * @package Dgame\Soap\Component\Bipro
 */
class ListShipments extends AbstractNode
{
    /**
     * ListShipments constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct();

        $this->attachAttribute(new XmlnsAttribute('transfer', 'http://www.bipro.net/namespace/transfer'));
        $this->attachElement($request);
    }
}