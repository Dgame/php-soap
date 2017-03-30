<?php

namespace Dgame\Soap\Test;

use function Dgame\Conditional\debug;
use Dgame\Soap\Hydrator\ClassMapper;
use Dgame\Soap\Hydrator\HydratableInterface;
use Dgame\Soap\Hydrator\HydrateProcedure;
use Dgame\Soap\Hydrator\Hydrator;
use Dgame\Soap\Test\Object\Address;
use Dgame\Soap\Test\Object\Car;
use Dgame\Soap\Test\Object\Person;
use Dgame\Soap\Test\Object\Phone;
use Dgame\Soap\Test\Object\Root;
use DOMDocument;
use PHPUnit\Framework\TestCase;

/**
 * Class HydrationTest
 */
final class HydrationTest extends TestCase
{
    /**
     * @var HydratableInterface[]
     */
    private $objects = [];

    public function setUp()
    {
        $doc = new DOMDocument();
        $doc->load(__DIR__ . '/dom.xml');

        $mapper = new ClassMapper(
            [
                'Root'    => Root::class,
                'Person'  => Person::class,
                'Car'     => Car::class,
                'Phone'   => Phone::class,
                'Address' => Address::class
            ]
        );
        $mapper->appendPattern('/^(?:soap\-?)?env(?:elope)?/iS', 'Root');

        $hydrator      = new Hydrator($mapper);
        $this->objects = $hydrator->hydrateDocument($doc);
    }

    public function testObjects()
    {
        $this->assertCount(1, $this->objects);

        $envelope = $this->objects[0];

        $this->assertNotNull($envelope);
        $this->assertInstanceOf(Root::class, $envelope);

        $this->validateRoot($envelope);
    }

    private function validateRoot(Root $root)
    {
        $persons = $root->getPersons();
        $this->assertCount(2, $persons);

        $this->assertInstanceOf(Person::class, $persons[0]);
        $this->assertEquals('Max Musterman', $persons[0]->getName());
        $this->assertInstanceOf(Car::class, $persons[0]->getCar());
        $this->assertEquals('BMW', $persons[0]->getCar()->getMarke());
        $this->assertNotEmpty($persons[0]->getCar()->getAttributes());
        $this->assertArrayHasKey('kennung', $persons[0]->getCar()->getAttributes());
        $this->assertEquals('i8', $persons[0]->getCar()->getAttribute('kennung'));
        $this->assertInstanceOf(Phone::class, $persons[0]->getPhone());
        $this->assertEquals('iPhone', $persons[0]->getPhone()->getName());
        $this->assertEquals(9, $persons[0]->getPhone()->getValue());
        $this->assertEquals('Hamburg', $persons[0]->getBirthplace());
        $this->assertInstanceOf(Address::class, $persons[0]->getAddress());
        $this->assertEquals('Hauptstraße 1', $persons[0]->getAddress()->getStreet());
        $this->assertEquals(245698, $persons[0]->getAddress()->getPlz());
        $this->assertEmpty($persons[0]->getAttributes());

        $this->assertInstanceOf(Person::class, $persons[1]);
        $this->assertEquals('Dr. Dolittle', $persons[1]->getName());
        $this->assertInstanceOf(Car::class, $persons[1]->getCar());
        $this->assertEquals('Audi', $persons[1]->getCar()->getMarke());
        $this->assertNotEmpty($persons[0]->getCar()->getAttributes());
        $this->assertArrayHasKey('kennung', $persons[0]->getCar()->getAttributes());
        $this->assertEquals('A3', $persons[1]->getCar()->getAttribute('kennung'));
        $this->assertInstanceOf(Phone::class, $persons[1]->getPhone());
        $this->assertEquals('Sony', $persons[1]->getPhone()->getName());
        $this->assertEquals('Xperia Z3', $persons[1]->getPhone()->getValue());
        $this->assertEquals('München', $persons[1]->getBirthplace());
        $this->assertInstanceOf(Address::class, $persons[1]->getAddress());
        $this->assertEquals('Partkstraße', $persons[1]->getAddress()->getStreet());
        $this->assertEquals(365494, $persons[1]->getAddress()->getPlz());
        $this->assertEmpty($persons[1]->getAttributes());
    }
}