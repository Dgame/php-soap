<?php

namespace Dgame\Soap\Test\Object;

use Dgame\Soap\Hydrator\Dom\AssemblableInterface;
use Dgame\Soap\XmlElement;
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
     * @return XmlElement
     */
    public function assemble(): XmlElement
    {
        $node = new XmlNode('soap-env');
        foreach ($this->persons as $person) {
            $node->appendElement($person->assemble());
        }

        return $node;
    }
}