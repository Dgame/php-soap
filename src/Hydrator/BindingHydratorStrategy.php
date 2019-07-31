<?php

namespace Dgame\Soap\Hydrator;

use Closure;
use function Dgame\Ensurance\ensure;
use Dgame\Soap\Attribute\AttributeInterface;
use Dgame\Soap\Element\ElementInterface;
use stdClass;
use Throwable;

/**
 * Class BindingHydratorStrategy
 * @package Dgame\Soap\Hydrator
 */
final class BindingHydratorStrategy implements HydratorStrategyInterface
{
    private const REPLACEMENTS = [
        '.*' => '\.(?:.+?)',
        '*.' => '(?:.+?)\.',
        '*'  => '(?:.*?)'
    ];

    /**
     * @var Closure[]
     */
    private $closures = [];
    /**
     * @var bool
     */
    private $caseSensitivev = true;
    /**
     * @var array
     */
    private $errors = [];

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function isCaseSensitive(): bool
    {
        return $this->caseSensitivev;
    }

    /**
     * @param bool $caseSensitivev
     */
    public function setCaseSensitive(bool $caseSensitivev): void
    {
        $this->caseSensitivev = $caseSensitivev;
    }

    /**
     * @param string $pattern
     *
     * @return string
     */
    private function getPattern(string $pattern): string
    {
        return sprintf('/^%s$/%s', $pattern, $this->isCaseSensitive() ? 'i' : null);
    }

    /**
     * @param string        $location
     * @param Closure       $closure
     * @param callable|null $factory
     *
     * @return stdClass
     * @throws \ReflectionException
     */
    public function bind(string $location, Closure $closure, callable $factory = null)
    {
        $location = preg_quote($location, '/');
        foreach (self::REPLACEMENTS as $key => $value) {
            $location = str_replace(preg_quote($key), $value, $location);
        }

        $object = $factory !== null ? $factory() : new stdClass();
        ensure($object)->isObject()->orThrow('%s is not an object', $object);

        $this->closures[$location] = $closure->bindTo($object);

        return $object;
    }

    /**
     * @param string             $footprints
     * @param AttributeInterface $attribute
     */
    public function setAttribute(string $footprints, AttributeInterface $attribute): void
    {
        $closure = $this->getMatchingClosure($footprints);
        if ($closure === null) {
            return;
        }

        try {
            $closure($attribute);
        } catch (Throwable $t) {
            $this->errors[] = $t->getMessage();
        }
    }

    /**
     * @param string           $footprints
     * @param ElementInterface $element
     *
     * @return bool
     */
    public function pushElement(string $footprints, ElementInterface $element): bool
    {
        $closure = $this->getMatchingClosure($footprints);
        if ($closure === null) {
            return false;
        }

        try {
            $closure($element);

            return true;
        } catch (Throwable $t) {
            $this->errors[] = $t->getMessage();
        }

        return false;
    }

    /**
     * @param string $footprints
     *
     * @return callable|null
     */
    private function getMatchingClosure(string $footprints): ?callable
    {
        foreach ($this->closures as $pattern => $closure) {
            if (preg_match($this->getPattern($pattern), $footprints) === 1) {
                return $closure;
            }
        }

        return null;
    }

    /**
     * @return bool
     */
    public function popElement(): bool
    {
        return true;
    }

    /**
     * @param string $footprint
     *
     * @return string
     */
    public function processFootprint(string $footprint): string
    {
        return $footprint;
    }
}
