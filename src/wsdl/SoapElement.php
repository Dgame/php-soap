<?php

namespace Dgame\Soap\Wsdl;

use Dgame\Soap\Wsdl\Elements\Restriction\RestrictionInterface;

/**
 * Class SoapElement
 * @package Dgame\Soap\Wsdl
 */
final class SoapElement
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $uri;
    /**
     * @var int
     */
    private $min;
    /**
     * @var int
     */
    private $max;
    /**
     * @var RestrictionInterface[]
     */
    private $restrictions = [];

    /**
     * Input constructor.
     *
     * @param string                 $name
     * @param int                    $min
     * @param int                    $max
     * @param RestrictionInterface[] $restrictions
     */
    public function __construct(string $name, int $min, int $max, array $restrictions)
    {
        $this->name         = $name;
        $this->min          = $min;
        $this->max          = $min;
        $this->restrictions = $restrictions;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri ?? '';
    }

    /**
     * @return int
     */
    public function getMin(): int
    {
        return $this->min;
    }

    /**
     * @return int
     */
    public function getMax(): int
    {
        return $this->max;
    }

    /**
     * @return array
     */
    public function getRestrictions(): array
    {
        return $this->restrictions;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return !$this->isVoluntary();
    }

    /**
     * @return bool
     */
    public function isVoluntary(): bool
    {
        return $this->min === 0;
    }

    /**
     * @param string $uri
     */
    public function setUri(string $uri)
    {
        $this->uri = $uri;
    }
}
