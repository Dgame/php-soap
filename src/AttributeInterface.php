<?php

namespace Dgame\Soap;

use Dgame\Soap\Visitor\AttributeVisitorInterface;

/**
 * Interface AttributeInterface
 * @package Dgame\Soap
 */
interface AttributeInterface
{
    /**
     * @return string
     */
    public function getName() : string;

    /**
     * @return string
     */
    public function getValue() : string;

    /**
     * @param AttributeVisitorInterface $visitor
     */
    public function accept(AttributeVisitorInterface $visitor);
}