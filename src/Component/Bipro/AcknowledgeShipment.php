<?php

namespace Dgame\Soap\Component\Bipro;

use Dgame\Soap\Attribute\XmlnsAttribute;
use Dgame\Soap\Component\AbstractNode;

/**
 * Class AckShipment
 * @package Dgame\Soap\Component\Bipro
 */
class AcknowledgeShipment extends AbstractNode
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
        $this->attachAttribute(new XmlnsAttribute('komposit', '"http://www.bipro.net/namespace/komposit'));
        $this->attachElement($request);
    }
}