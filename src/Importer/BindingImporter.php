<?php

namespace Dgame\Soap\Importer;

use Closure;
use Dgame\Soap\Element\ElementInterface;
use Dgame\Soap\Element\XmlElementInterface;
use Dgame\Soap\Element\XmlNodeInterface;
use Dgame\Soap\Visitor\ElementVisitorInterface;
use SplStack;
use Throwable;

/**
 * Class BindingImporter
 * @package Dgame\Soap\Importer
 */
final class BindingImporter implements ElementVisitorInterface
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
     * @var SplStack
     */
    private $footprints;
    /**
     * @var bool
     */
    public $debug = false;

    /**
     * BindingImporter constructor.
     */
    public function __construct()
    {
        $this->footprints = new SplStack();
    }

    /**
     * @return string
     */
    private function getFootprints(): string
    {
        $footprints = [];
        for ($i = $this->footprints->count() - 1; $i >= 0; $i--) {
            $footprints[] = $this->footprints[$i];
        }

        return implode('.', $footprints);
    }

    /**
     * @param ElementInterface $element
     *
     * @return bool
     */
    private function pushElement(ElementInterface $element): bool
    {
        $this->footprints->push($element->getName());
        $footprints = $this->getFootprints();

        if ($this->debug) {
            var_dump($footprints);
        }

        return $this->try($footprints, $element);
    }

    /**
     *
     */
    private function popElement(): void
    {
        $this->footprints->pop();
    }

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
     * @param string  $location
     * @param Closure $closure
     *
     * @return BindingInterface
     */
    public function bind(string $location, Closure $closure): BindingInterface
    {
        $location = preg_quote($location, '/');
        foreach (self::REPLACEMENTS as $key => $value) {
            $location = str_replace(preg_quote($key), $value, $location);
        }

        $delegate = new ImportBindingDelegate($closure);

        $this->closures[$location] = $delegate;

        return $delegate;
    }

    /**
     * @param string           $footprints
     * @param ElementInterface $element
     *
     * @return bool
     */
    public function try(string $footprints, ElementInterface $element): bool
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
     * @param ElementInterface $element
     */
    public function visitElement(ElementInterface $element): void
    {
        $this->pushElement($element);
        $this->popElement();
    }

    /**
     * @param XmlElementInterface $element
     */
    public function visitXmlElement(XmlElementInterface $element): void
    {
        $this->visitElement($element);
    }

    /**
     * @param XmlNodeInterface $node
     */
    public function visitXmlNode(XmlNodeInterface $node): void
    {
        $this->pushElement($node);
        foreach ($node->getElements() as $element) {
            $element->accept($this);
        }
        $this->popElement();
    }
}
