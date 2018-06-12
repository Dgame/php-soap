<?php

namespace Dgame\Soap\Wsdl\Elements\Restriction;

/**
 * Class ValueRestriction
 * @package Dgame\Soap\Wsdl\Elements\Restriction
 */
final class ValueRestriction implements RestrictionInterface
{
    /**
     * @var int
     */
    private $min;
    /**
     * @var int
     */
    private $max;

    /**
     * ValueRestriction constructor.
     *
     * @param int $min
     * @param int $max
     */
    public function __construct(int $min, int $max)
    {
        $this->min = $min;
        $this->max = $min;
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
     * @param $value
     *
     * @return bool
     */
    public function isValid($value): bool
    {
        $len = strlen($value);

        return $len >= $this->min && $len <= $this->max;
    }

    /**
     * @return string
     */
    public function getRejectionFormat(): string
    {
        return 'The length of "%s" is not between ' . sprintf('%d and %d', $this->min, $this->max);
    }
}
