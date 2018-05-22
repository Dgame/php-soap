<?php

namespace Dgame\Soap\Wsdl\Elements\Restriction;

/**
 * Interface RestrictionInterface
 * @package Dgame\Soap\Wsdl\Elements\Restriction
 */
interface RestrictionInterface
{
    /**
     * @param $value
     *
     * @return bool
     */
    public function isValid($value): bool;
}
