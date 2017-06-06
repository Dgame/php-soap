<?php

namespace Dgame\Soap\Hydrator;

use Dgame\Object\ObjectFacade;
use Dgame\Soap\Element;
use Dgame\Variants\Variants;
use Monolog\Handler\NullHandler;
use Monolog\Logger;
use Monolog\Registry;

/**
 * Class Hydrate
 * @package Dgame\Soap\Hydrator
 */
final class Hydrate
{
    /**
     * @var ObjectFacade
     */
    private $facade;
    /**
     * @var Element
     */
    private $element;

    /**
     * Hydrate constructor.
     *
     * @param ClassMapper $mapper
     * @param Element     $element
     */
    public function __construct(ClassMapper $mapper, Element $element)
    {
        $object = $mapper->new($element->getName());
        if ($object !== null) {
            $this->facade = new ObjectFacade($object);
        }

        $this->element = $element;

        self::verifyLoggerPresence();
    }

    /**
     *
     */
    private static function verifyLoggerPresence()
    {
        if (!Registry::hasLogger(Hydrator::class)) {
            $log = new Logger(Hydrator::class);
            $log->pushHandler(new NullHandler());
            Registry::addLogger($log);
        }
    }

    /**
     * @return ObjectFacade
     */
    public function getFacade(): ObjectFacade
    {
        if (!$this->hasFacade()) {
            Registry::getInstance(Hydrator::class)->error('Invalid Hydrate in use');
        }

        return $this->facade;
    }

    /**
     * @return Element
     */
    public function getElement(): Element
    {
        return $this->element;
    }

    /**
     * @return bool
     */
    public function hasFacade(): bool
    {
        return $this->facade !== null;
    }

    /**
     * @param string $name
     * @param        $value
     */
    public function assign(string $name, $value)
    {
        if (!$this->getFacade()->setValue($name, $value)) {
            Registry::getInstance(Hydrator::class)->warning(
                'Could not assign value {value} of {name}',
                ['value' => var_export($value, true), 'name' => $name]
            );
        }
    }

    /**
     * @param Hydrate $hydrate
     */
    public function append(self $hydrate)
    {
        $facade  = $hydrate->getFacade();
        $class   = $facade->getReflection()->getShortName();
        $element = $hydrate->getElement()->getName();

        foreach (Variants::ofArguments($class, $element)->withCamelSnakeCase() as $name) {
            if ($this->getFacade()->setValue($name, $facade->getObject())) {
                return;
            }
        }

        Registry::getInstance(Hydrator::class)->warning(
            'Could not append object {name}',
            ['name' => $element]
        );
    }
}