<?php

namespace Dgame\Soap\Component;

use Dgame\Optional\Optional;
use Dgame\Soap\Element\Element;
use Dgame\Soap\element\XmlElement;
use Dgame\Soap\Element\XmlNode;
use function Dgame\Wrapper\assoc;
use function Dgame\Wrapper\string;

/**
 * Class AbstractNode
 * @package Dgame\Soap\Component
 */
abstract class AbstractNode extends XmlNode
{
    /**
     * AbstractNode constructor.
     */
    public function __construct()
    {
        $class = string(static::class)->namespaceInfo()->getClass();

        parent::__construct($class);
    }

    /**
     * @param string $name
     *
     * @return array
     */
    final public function findAllElementsByName(string $name): array
    {
        return assoc($this->getElements())->filter(function (Element $element) use ($name) {
            return $element->getName() === $name;
        })->get();
    }

    /**
     * @param string $name
     *
     * @return Optional
     */
    final public function findElementByName(string $name): Optional
    {
        $result = $this->findAllElementsByName($name);

        return assoc($result)->popFront();
    }

    /**
     * @param string $name
     * @param string $value
     */
    final public function setXmlValue(string $name, string $value)
    {
        /** @var Element $element */
        if ($this->findElementByName($name)->isSome($element)) {
            $element->setValue($value);
        } else {
            $this->attachElement(new XmlElement($name, $value));
        }
    }
}