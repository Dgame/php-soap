<?php

namespace Dgame\Soap\Component\Bipro;

use Dgame\Soap\Component\AbstractNode;
use Dgame\Soap\Attribute\XmlnsAttribute;

/**
 * Class GetShipment
 * @package Dgame\Soap\Component\Bipro
 */
class GetShipment extends AbstractNode
{
    /**
     * GetShipment constructor.
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