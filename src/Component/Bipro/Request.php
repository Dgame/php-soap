<?php

namespace Dgame\Soap\Component\Bipro;

use Dgame\Soap\Component\AbstractNode;

/**
 * Class Request
 * @package Dgame\Soap\Component\Bipro
 */
class Request extends AbstractNode
{
    /**
     * Request constructor.
     *
     * @param Version $version
     */
    public function __construct(Version $version)
    {
        parent::__construct();

        $this->attachElement($version);
    }

    /**
     * @param string $consumerId
     */
    final public function setConsumerID(string $consumerId)
    {
        $this->setXmlValue('ConsumerID', $consumerId);
    }

    /**
     * @param string $id
     */
    final public function setID(string $id)
    {
        $this->setXmlValue('ID', $id);
    }

    /**
     * @param boolean $value
     */
    final public function setBestaetigeLieferungen(bool $value)
    {
        $this->setXmlValue('BestaetigeLieferungen', (int) $value);
    }
}