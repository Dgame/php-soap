<?php

namespace Dgame\Soap\Test\Object;

use Dgame\Soap\Hydrator\Dom\AssemblableInterface;
use Dgame\Soap\XmlElement;
use Dgame\Soap\XmlNode;

/**
 * Class TestRoot
 * @package Dgame\Soap\Test\Object
 */
final class TestRoot implements AssemblableInterface
{
    /**
     * @var TestPerson[]
     */
    private $persons = [];

    /**
     * @param TestPerson $person
     */
    public function appendTestPerson(TestPerson $person)
    {
        $this->persons[] = $person;
    }

    /**
     * @return TestPerson[]
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