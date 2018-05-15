<?php

namespace Dgame\Soap\Hydrator;

use Dgame\Soap\Attribute\AttributeInterface;
use Dgame\Soap\Element\ElementInterface;
use SplStack;

/**
 * Class DefaultHydratorStrategy
 * @package Dgame\Soap\Hydrator
 */
final class DefaultHydratorStrategy implements HydratorStrategyInterface
{
    /**
     * @var SplStack
     */
    private $elements;
    /**
     * @var object
     */
    private $top;
    /**
     * @var callable[]
     */
    private $callbacks = [];
    /**
     * @var callable[]
     */
    private $pattern = [];

    /**
     * DefaultHydratorStrategy constructor.
     */
    public function __construct()
    {
        $this->elements = new SplStack();
    }

    /**
     * @return SplStack
     */
    public function getElements(): SplStack
    {
        return $this->elements;
    }

    /**
     * @param string   $footprints
     * @param callable $callback
     *
     * @return DefaultHydratorStrategy
     */
    public function setCallback(string $footprints, callable $callback): self
    {
        $this->callbacks[$footprints] = $callback;

        return $this;
    }

    /**
     * @param string   $pattern
     * @param callable $callback
     *
     * @return DefaultHydratorStrategy
     */
    public function setFootprintPattern(string $pattern, callable $callback): self
    {
        $this->pattern[$pattern] = $callback;

        return $this;
    }

    /**
     * @return object|null
     */
    private function peek()
    {
        return $this->elements->isEmpty() ? null : $this->elements->top();
    }

    /**
     * @return object|null
     */
    public function top()
    {
        return $this->top ?? $this->peek();
    }

    /**
     * @param string             $footprints
     * @param AttributeInterface $attribute
     */
    public function setAttribute(string $footprints, AttributeInterface $attribute): void
    {
        if ($this->hasFootprintsCallback($footprints)) {
            $this->getFootprintsCallback($footprints)($attribute, $this->peek());
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
        if ($this->hasFootprintsCallback($footprints)) {
            $result = $this->getFootprintsCallback($footprints)($element, $this->peek());
            if (is_object($result)) {
                $this->elements->push($result);

                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function popElement(): bool
    {
        if ($this->elements->isEmpty()) {
            return false;
        }

        $this->top = $this->elements->pop();

        return true;
    }

    /**
     * @param string $footprint
     *
     * @return string
     */
    public function processFootprint(string $footprint): string
    {
        foreach ($this->pattern as $pattern => $callback) {
            if (preg_match($pattern, $footprint) === 1) {
                return $callback($footprint);
            }
        }

        return $footprint;
    }

    /**
     * @param string $footprints
     *
     * @return bool
     */
    private function hasFootprintsCallback(string $footprints): bool
    {
        return array_key_exists($footprints, $this->callbacks);
    }

    /**
     * @param string $footprints
     *
     * @return callable|null
     */
    private function getFootprintsCallback(string $footprints): ?callable
    {
        if ($this->hasFootprintsCallback($footprints)) {
            return $this->callbacks[$footprints];
        }

        return null;
    }
}
