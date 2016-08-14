<?php

namespace Dgame\Soap\Bipro;

use Dgame\Soap\Element;

/**
 * Class BiProVersion
 * @package Dgame\Soap\Bipro
 */
class BiProVersion extends Element
{
    /**
     * BiProVersion constructor.
     *
     * @param string $version
     */
    public function __construct(string $version)
    {
        parent::__construct('BiPROVersion', $version);

        $this->appendAttributes(
            [
                'xmlns' => [
                    'allgemein' => 'http://www.bipro.net/namespace/allgemein'
                ]
            ]
        );
    }
}