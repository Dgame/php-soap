<?php

namespace Dgame\Soap\Component\Bipro;

use Dgame\Soap\XmlNode;

/**
 * Class Request
 * @package Dgame\Soap\Component\Bipro
 */
class Request extends XmlNode
{
    /**
     * @var Version
     */
    private $biproVersion;
    /**
     * @var string
     */
    private $consumerId = '';
    /**
     * @var string
     */
    private $id = '';
    /**
     * @var bool
     */
    private $bestaetigeLieferungen = false;

    /**
     * Request constructor.
     *
     * @param Version $version
     */
    public function __construct(Version $version)
    {
        parent::__construct();

        $this->biproVersion = $version;
    }

    /**
     * @return Version
     */
    final public function getBiproVersion(): Version
    {
        return $this->biproVersion;
    }

    /**
     * @param string $consumerId
     */
    final public function setConsumerId(string $consumerId)
    {
        $this->consumerId = $consumerId;
    }

    /**
     * @return null|string
     */
    final public function getConsumerId()
    {
        return $this->consumerId;
    }

    /**
     * @param string $id
     */
    final public function setId(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return null|string
     */
    final public function getId()
    {
        return $this->id;
    }

    /**
     * @param boolean $bestaetigeLieferungen
     */
    final public function setBestaetigeLieferungen(bool $bestaetigeLieferungen)
    {
        $this->bestaetigeLieferungen = $bestaetigeLieferungen;
    }

    /**
     * @return boolean
     */
    final public function bestaetigeLieferungen(): bool
    {
        return $this->bestaetigeLieferungen;
    }

    /**
     * @return array
     */
    public function export(): array
    {
        return [
            'biproVersion',
            'consumerId' => 'ConsumerID',
            'id'         => 'ID'
        ];
    }
}