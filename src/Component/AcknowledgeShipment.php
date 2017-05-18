<?php

namespace Dgame\Soap\Component;

use Dgame\Soap\Attribute\XmlnsAttribute;

/**
 * Class AcknowledgeShipment
 * @package Dgame\Soap\Component
 */
final class AcknowledgeShipment extends NamedNode
{
    /**
     * AcknowledgeShipment constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct('transfer');

        $this->setAttribute(new XmlnsAttribute('transfer', 'http://www.bipro.net/namespace/transfer'));
        $this->appendElement($request);
    }
}