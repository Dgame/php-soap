<?php

namespace Dgame\Soap\Hydrator;

use Dgame\Object\ObjectFacade;
use Dgame\Soap\AssignableInterface;

/**
 * Class Hydrate
 * @package Dgame\Soap\Hydrator
 */
final class Hydrate extends ObjectFacade
{
    /**
     * @var string
     */
    private $name;

    /**
     * Hydrate constructor.
     *
     * @param string $name
     * @param string $class
     */
    public function __construct(string $name, string $class)
    {
        parent::__construct(new $class());

        $this->name = $name;
    }

    /**
     * @param string $name
     * @param string $class
     *
     * @return Hydrate
     */
    public static function new(string $name, string $class): self
    {
        return new self($name, $class);
    }

    /**
     * @param Hydrate $hydrate
     *
     * @return bool
     */
    public function append(self $hydrate): bool
    {
        return $this->setValueWithVariantNames([$hydrate->getName(), $hydrate->getClassName()], $hydrate->getObject());
    }

    /**
     * @param AssignableInterface $assignable
     *
     * @return bool
     */
    public function assign(AssignableInterface $assignable): bool
    {
        if ($assignable->hasValue()) {
            return $this->setValueWithVariantNames([$assignable->getName()], $assignable->getValue());
        }

        return false;
    }

    /**
     * @param array $names
     * @param       $value
     *
     * @return bool
     */
    private function setValueWithVariantNames(array $names, $value)
    {
        foreach (self::getAppendNames($names) as $name) {
            if ($this->setValue($name, $value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $names
     *
     * @return array
     */
    private static function getAppendNames(array $names): array
    {
        $output = [];
        foreach ($names as $name) {
            $output[] = lcfirst($name);
            $output[] = ucfirst($name);
        }

        return $output;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->getReflection()->getShortName();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}