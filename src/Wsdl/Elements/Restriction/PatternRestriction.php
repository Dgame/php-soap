<?php

namespace Dgame\Soap\Wsdl\Elements\Restriction;

/**
 * Class PatternRestriction
 * @package Dgame\Soap\Wsdl\Elements\Restriction
 */
final class PatternRestriction implements RestrictionInterface
{
    /**
     * @var string
     */
    private $pattern;

    /**
     * PatternRestriction constructor.
     *
     * @param string $pattern
     */
    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @return string
     */
    public function getPattern(): string
    {
        return $this->pattern;
    }

    /**
     * @param $value
     *
     * @return bool
     */
    public function isValid($value): bool
    {
        return preg_match(sprintf('/%s/', $this->pattern), $value) === 1;
    }

    /**
     * @return string
     */
    public function getRejectionFormat(): string
    {
        return '"%s" does not match ' . $this->pattern;
    }
}
