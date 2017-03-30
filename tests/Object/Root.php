<?php

namespace Dgame\Soap\Test\Object;

use Dgame\Soap\Hydrator\Hydratable;

/**
 * Class Root
 * @package Dgame\Soap\Test\Object
 */
final class Root extends Hydratable
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
}