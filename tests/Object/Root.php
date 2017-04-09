<?php

namespace Dgame\Soap\Test\Object;

use Dgame\Soap\Element;
use Dgame\Soap\Hydrator\Dom\AssemblableInterface;
use Dgame\Soap\XmlNode;

/**
 * Class Root
 * @package Dgame\Soap\Test\Object
 */
final class Root implements AssemblableInterface
{
    /**
     * @var Person[]
     */
    private $persons = [];

    /**
     * @param Person $person
     */
    public function appendPerson(Person $person)
    {
        $this->persons[] = $person;
    }

    /**
     * @return Person[]
     */
    public function getPersons(): array
    {
        return $this->persons;
    }

    /**
     * @return Element
     */
    public function assemble(): Element
    {
        $node = new XmlNode('soap-env');
        foreach ($this->persons as $person) {
            $node->appendChild($person->assemble());
        }

        return $node;
    }
}