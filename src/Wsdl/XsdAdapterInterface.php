<?php

namespace Dgame\Soap\Wsdl;

use Dgame\Soap\Wsdl\Elements\Element;

/**
 * Interface XsdInterface
 * @package Dgame\Soap\Wsdl
 */
interface XsdAdapterInterface
{
    /**
     * @param string $name
     *
     * @return Element|null
     */
    public function findElementByNameInDeep(string $name): ?Element;

    /**
     * @param string $prefix
     *
     * @return string
     */
    public function getUriByPrefix(string $prefix): string;
}
