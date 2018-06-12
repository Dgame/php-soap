<?php

namespace Dgame\Soap\Wsdl\Elements\Restriction;

use DOMElement;
use function Dgame\Ensurance\enforce;

/**
 * Class Restriction
 * @package Dgame\Soap\Wsdl\Elements
 */
final class RestrictionFactory
{
    /**
     * @param DOMElement $element
     *
     * @return RestrictionInterface
     */
    public static function createFrom(DOMElement $element): RestrictionInterface
    {
        $restriction = self::createValueRestriction($element) ??
                       self::createEnumRestriction($element) ??
                       self::createLengthRestriction($element) ??
                       self::createPatternRestriction($element);

        enforce($restriction !== null)->orThrow('Could not detect Restriction');

        return $restriction;
    }

    /**
     * @param DOMElement $element
     *
     * @return RestrictionInterface|null
     */
    public static function createLengthRestriction(DOMElement $element): ?RestrictionInterface
    {
        $length = $element->getElementsByTagName('length');
        if ($length->length !== 0) {
            $len = (int) $length->item(0)->getAttribute('value');

            return LengthRestriction::exact($len);
        }

        $minLength = $element->getElementsByTagName('minLength');
        $maxLength = $element->getElementsByTagName('maxLength');
        if ($minLength->length !== 0 || $maxLength->length !== 0) {
            $min = $minLength->length !== 0 ? (int) $minLength->item(0)->getAttribute('value') : 0;
            $max = $maxLength->length !== 0 ? (int) $maxLength->item(0)->getAttribute('value') : PHP_INT_MAX;

            return LengthRestriction::within($min, $max);
        }

        return null;
    }

    /**
     * @param DOMElement $element
     *
     * @return RestrictionInterface|null
     */
    public static function createPatternRestriction(DOMElement $element): ?RestrictionInterface
    {
        $pattern = $element->getElementsByTagName('pattern');
        if ($pattern->length === 0) {
            return null;
        }

        return new PatternRestriction($pattern->item(0)->getAttribute('value'));
    }

    /**
     * @param DOMElement $element
     *
     * @return RestrictionInterface|null
     */
    public static function createEnumRestriction(DOMElement $element): ?RestrictionInterface
    {
        $enum = $element->getElementsByTagName('enumeration');
        if ($enum->length === 0) {
            return null;
        }

        $values = [];
        for ($i = 0, $c = $enum->length; $i < $c; $i++) {
            $values[] = $enum->item($i)->getAttribute('value');
        }

        return new EnumRestriction($values);
    }

    /**
     * @param DOMElement $element
     *
     * @return RestrictionInterface|null
     */
    public static function createValueRestriction(DOMElement $element): ?RestrictionInterface
    {
        $minValue = $element->getElementsByTagName('minInclusive');
        $maxValue = $element->getElementsByTagName('maxInclusive');
        if ($minValue->length !== 0 || $maxValue->length !== 0) {
            $min = $minValue->length !== 0 ? (int) $minValue->item(0)->getAttribute('value') : 0;
            $max = $maxValue->length !== 0 ? (int) $maxValue->item(0)->getAttribute('value') : PHP_INT_MAX;

            return new ValueRestriction($min, $max);
        }

        return null;
    }
}
