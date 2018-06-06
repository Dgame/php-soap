<?php

namespace Dgame\Soap\Wsdl;

use Dgame\Soap\Wsdl\Elements\Element;

/**
 * Class WsdlXsdFacade
 * @package Dgame\Soap\Wsdl
 */
final class WsdlXsdAdapter implements XsdAdapterInterface
{
    /**
     * @var Wsdl
     */
    private $wsdl;

    /**
     * WsdlXsdAdapter constructor.
     *
     * @param Wsdl $wsdl
     */
    public function __construct(Wsdl $wsdl)
    {
        $this->wsdl = $wsdl;
    }

    /**
     * @param string $name
     *
     * @return Element|null
     */
    public function findElementByNameInDeep(string $name): ?Element
    {
        return $this->wsdl->findOneElementInSchemas($name);
    }

    /**
     * @param string $prefix
     *
     * @return string
     */
    public function getUriByPrefix(string $prefix): string
    {
        return $this->wsdl->getLocation();
    }
}
