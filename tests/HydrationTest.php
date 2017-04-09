<?php

namespace Dgame\Soap\Test;

use Dgame\Soap\Hydrator\ClassMapper;
use Dgame\Soap\Hydrator\Dom\Hydrator;
use Dgame\Soap\Test\Object\Address;
use Dgame\Soap\Test\Object\Body;
use Dgame\Soap\Test\Object\Car;
use Dgame\Soap\Test\Object\Envelope;
use Dgame\Soap\Test\Object\Fault;
use Dgame\Soap\Test\Object\Ort;
use Dgame\Soap\Test\Object\OrtsTeil;
use Dgame\Soap\Test\Object\Person;
use Dgame\Soap\Test\Object\Phone;
use Dgame\Soap\Test\Object\Result;
use Dgame\Soap\Test\Object\Root;
use Dgame\Soap\Test\Object\Stammdaten;
use Dgame\Soap\Test\Object\Strassen;
use DOMDocument;
use PHPUnit\Framework\TestCase;

/**
 * Class HydrationTest
 */
final class HydrationTest extends TestCase
{
    public function testObjects()
    {
        $doc = new DOMDocument();
        $doc->load(__DIR__ . '/xml/test1.xml');

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

        $hydrator = new Hydrator($mapper);
        $objects  = $hydrator->hydrateDocument($doc);

        $this->assertCount(1, $objects);

        /** @var Root $root */
        $root = $objects[0];

        $this->assertNotNull($root);
        $this->assertInstanceOf(Root::class, $root);

        $persons = $root->getPersons();
        $this->assertCount(2, $persons);

        $this->assertInstanceOf(Person::class, $persons[0]);
        $this->assertEquals('Max Musterman', $persons[0]->getName());
        $this->assertInstanceOf(Car::class, $persons[0]->getCar());
        $this->assertEquals('BMW', $persons[0]->getCar()->getMarke());
        $this->assertNotEmpty($persons[0]->getCar()->kennung);
        $this->assertEquals('i8', $persons[0]->getCar()->kennung);
        $this->assertInstanceOf(Phone::class, $persons[0]->getPhone());
        $this->assertEquals('iPhone', $persons[0]->getPhone()->getName());
        $this->assertEquals(9, $persons[0]->getPhone()->getValue());
        $this->assertEquals('Hamburg', $persons[0]->getBirthplace());
        $this->assertInstanceOf(Address::class, $persons[0]->getAddress());
        $this->assertEquals('Hauptstraße 1', $persons[0]->getAddress()->getStreet());
        $this->assertEquals(245698, $persons[0]->getAddress()->getPlz());

        $this->assertInstanceOf(Person::class, $persons[1]);
        $this->assertEquals('Dr. Dolittle', $persons[1]->getName());
        $this->assertInstanceOf(Car::class, $persons[1]->getCar());
        $this->assertEquals('Audi', $persons[1]->getCar()->getMarke());
        $this->assertNotEmpty($persons[0]->getCar()->kennung);
        $this->assertEquals('A3', $persons[1]->getCar()->kennung);
        $this->assertInstanceOf(Phone::class, $persons[1]->getPhone());
        $this->assertEquals('Sony', $persons[1]->getPhone()->getName());
        $this->assertEquals('Xperia Z3', $persons[1]->getPhone()->getValue());
        $this->assertEquals('München', $persons[1]->getBirthplace());
        $this->assertInstanceOf(Address::class, $persons[1]->getAddress());
        $this->assertEquals('Partkstraße', $persons[1]->getAddress()->getStreet());
        $this->assertEquals(365494, $persons[1]->getAddress()->getPlz());
    }

    public function testWithoutFirstObject()
    {
        $doc = new DOMDocument();
        $doc->loadXml('<root><Car marke="Mercedes" /></root>');

        $mapper = new ClassMapper(
            [
                'Car' => Car::class
            ]
        );

        $hydrator = new Hydrator($mapper);
        $objects  = $hydrator->hydrateDocument($doc);

        $this->assertCount(1, $objects);
        $this->assertArrayHasKey(0, $objects);
        $this->assertInstanceOf(Car::class, $objects[0]);
        /** @var Car $car */
        $car = $objects[0];
        $this->assertEquals('Mercedes', $car->getMarke());
    }

    public function testPropertyAssignment()
    {
        $doc = new DOMDocument();
        $doc->load(__DIR__ . '/xml/test2.xml');

        $mapper = new ClassMapper(
            [
                'Stammdaten' => Stammdaten::class
            ]
        );

        $hydrator = new Hydrator($mapper);
        $objects  = $hydrator->hydrateDocument($doc);

        $this->assertCount(1, $objects);
        $this->assertArrayHasKey(0, $objects);
        $this->assertInstanceOf(Stammdaten::class, $objects[0]);

        /** @var Stammdaten $stammdaten */
        $stammdaten = $objects[0];

        $this->assertEquals('Muster', $stammdaten->Name);
        $this->assertEquals('Max', $stammdaten->Vorname);
    }

    public function testFault()
    {
        $doc = new DOMDocument();
        $doc->load(__DIR__ . '/xml/fault.xml');

        $mapper = new ClassMapper(
            [
                'Envelope' => Envelope::class,
                'Body'     => Body::class,
                'Fault'    => Fault::class
            ]
        );

        $hydrator = new Hydrator($mapper);
        $objects  = $hydrator->hydrateDocument($doc);

        $this->assertCount(1, $objects);
        $this->assertArrayHasKey(0, $objects);
        $this->assertInstanceOf(Envelope::class, $objects[0]);

        /** @var Envelope $envelope */
        $envelope = $objects[0];

        $this->assertTrue($envelope->getBody()->hasFault());
        $this->assertEquals('Fehler!', $envelope->getBody()->getFault()->getFaultcode());
        $this->assertEquals('Es ist ein Fehler aufgetreten', $envelope->getBody()->getFault()->getFaultstring());
    }

    public function testList()
    {
        $doc = new DOMDocument();
        $doc->load(__DIR__ . '/xml/list.xml');

        $mapper = new ClassMapper(
            [
                'Envelope' => Envelope::class,
                'Body'     => Body::class,
                'Fault'    => Fault::class,
                'Result'   => Result::class,
                'Ort'      => Ort::class,
                'Oteil'    => OrtsTeil::class,
                'Strassen' => Strassen::class
            ]
        );

        $hydrator = new Hydrator($mapper);
        $objects  = $hydrator->hydrateDocument($doc);

        $this->assertCount(1, $objects);
        $this->assertArrayHasKey(0, $objects);
        $this->assertInstanceOf(Envelope::class, $objects[0]);

        /** @var Envelope $envelope */
        $envelope = $objects[0];

        $this->assertFalse($envelope->getBody()->hasFault());
        $this->assertInstanceOf(Result::class, $envelope->getBody()->getResult());
        $this->assertCount(1, $envelope->getBody()->getResult()->getOrte());
        $this->assertEquals('Hamburg', $envelope->getBody()->getResult()->getOrte()[0]->getName());
        $this->assertCount(4, $envelope->getBody()->getResult()->getOrte()[0]->getOrtsteile());
        $this->assertEquals('Hamburg-Altstadt', $envelope->getBody()->getResult()->getOrte()[0]->getOrtsteile()[1]->getName());

        for ($i = 0; $i < 4; $i++) {
            $this->assertNotEmpty($envelope->getBody()->getResult()->getOrte()[0]->getOrtsteile()[$i]->getStrassen());
        }
    }
}