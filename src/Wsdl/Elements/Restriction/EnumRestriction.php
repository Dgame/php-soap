<?php

namespace Dgame\Soap\Wsdl\Elements\Restriction;

/**
 * Class EnumRestriction
 * @package Dgame\Soap\Wsdl\Elements\Restriction
 */
final class EnumRestriction implements RestrictionInterface
{
    /**
     * @var array
     */
    private $values = [];

    /**
     * EnumRestriction constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @param $value
     *
     * @return bool
     */
    public function isValid($value): bool
    {
        return in_array($value, $this->values);
    }

    /**
     * @return string
     */
    public function getRejectionFormat(): string
    {
        return '"%s" is not in range of ' . print_r($this->values, true);
    }
}
