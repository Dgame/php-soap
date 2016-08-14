<?php

namespace Dgame\Soap\Component\Bipro;
use Dgame\Soap\Node;

/**
 * Class Request
 * @package Dgame\Soap\Component\Bipro
 */
class Request extends Node
{
    /**
     * @var Version|null
     */
    public $biproVersion = null;
    /**
     * @var null|string
     */
    public $consumerId = null;
    /**
     * @var null|string
     */
    public $id = null;
    /**
     * @var bool
     */
    public $bestaetigeLieferungen = false;

    /**
     * Request constructor.
     *
     * @param Version $version
     */
    public function __construct(Version $version)
    {
        parent::__construct();

        $this->setPropertyAlias('consumerId', 'ConsumerID');
        $this->setPropertyAlias('id', 'ID');

        $this->biproVersion = $version;
    }
}