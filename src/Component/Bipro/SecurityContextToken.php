<?php

namespace Dgame\Soap\Component\Bipro;

use Dgame\Soap\XmlNode;
use Dgame\Soap\XmlnsAttribute;

/**
 * Class SecurityContextToken
 * @package Dgame\Soap\Component\Bipro
 */
class SecurityContextToken extends XmlNode
{
    /**
     * @var string
     */
    private $id;

    /**
     * SecurityContextToken constructor.
     *
     * @param string $id
     */
    public function __construct(string $id)
    {
        parent::__construct();

        $this->id = $id;

        $this->setAttribute(new XmlnsAttribute('wsc', 'http://schemas.xmlsoap.org/ws/2005/02/sc'));
    }

    /**
     * @return string
     */
    final public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function export(): array
    {
        return ['id' => 'Identifier'];
    }
}