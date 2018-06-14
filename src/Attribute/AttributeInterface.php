<?php

namespace Dgame\Soap\Attribute;

use Dgame\Soap\NamedInterface;
use Dgame\Soap\ValuedInterface;
use Dgame\Soap\Visitor\AttributeVisitableInterface;

/**
 * Interface AttributeInterface
 * @package Soap\Attribute
 */
interface AttributeInterface extends NamedInterface, ValuedInterface, AttributeVisitableInterface
{
}
