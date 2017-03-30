<?php

namespace Dgame\Soap\Hydrator;

use ICanBoogie\Inflector;

/**
 * Class Method
 * @package Dgame\Soap\Hydrator
 */
final class Method
{
    const PREFIXES = ['set', 'append'];

    /**
     * @var object
     */
    private $object;
    /**
     * @var null|string
     */
    private $method;

    /**
     * Method constructor.
     *
     * @param string $postifx
     * @param object $object
     */
    public function __construct(string $postifx, $object)
    {
        $this->object = $object;
        $this->method = $this->getMethod($postifx);
    }

    /**
     * @param string $postfix
     * @param object $object
     *
     * @return Method
     */
    public static function of(string $postfix, $object): self
    {
        return new self($postfix, $object);
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->method !== null;
    }

    /**
     * @param $value
     *
     * @return bool
     */
    public function assign($value): bool
    {
        if ($this->isValid()) {
            call_user_func([$this->object, $this->method], $value);

            return true;
        }

        return false;
    }

    /**
     * @param string $postfix
     *
     * @return null|string
     */
    private function getMethod(string $postfix)
    {
        foreach (self::PREFIXES as $prefix) {
            $method = $prefix . Inflector::get()->camelize($postfix, Inflector::UPCASE_FIRST_LETTER);
            if (method_exists($this->object, $method)) {
                return $method;
            }
        }

        return null;
    }
}