<?php

namespace Dgame\Soap\Test;

use Dgame\Soap\Hydrator\ClassMapper;
use Dgame\Soap\Hydrator\Dom\Hydrator;
use Dgame\Soap\Test\Object\TestAddress;
use Dgame\Soap\Test\Object\TestBody;
use Dgame\Soap\Test\Object\TestCar;
use Dgame\Soap\Test\Object\TestEnvelope;
use Dgame\Soap\Test\Object\TestFault;
use Dgame\Soap\Test\Object\TestHobby;
use Dgame\Soap\Test\Object\TestOrt;
use Dgame\Soap\Test\Object\TestOrtsTeil;
use Dgame\Soap\Test\Object\TestPerson;
use Dgame\Soap\Test\Object\TestPhone;
use Dgame\Soap\Test\Object\TestResult;
use Dgame\Soap\Test\Object\TestRoot;
use Dgame\Soap\Test\Object\TestStammdaten;
use Dgame\Soap\Test\Object\TestStrassen;
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
                'Root'    => TestRoot::class,
                'Person'  => TestPerson::class,
                'Car'     => TestCar::class,
                'Phone'   => TestPhone::class,
                'Address' => TestAddress::class
            ]
        );
        $mapper->appendPattern('/^(?:soap\-?)?env(?:elope)?/iS', 'Root');

        $hydrator = new Hydrator($mapper);
        /** @var TestRoot $root */
        $root = $hydrator->hydrateDocument($doc);

        $this->assertNotNull($root);
        $this->assertInstanceOf(TestRoot::class, $root);

        /** @var TestPerson[] $persons */
        $persons = $root->getPersons();
        $this->assertCount(2, $persons);

        $this->assertInstanceOf(TestPerson::class, $persons[0]);
        $this->assertEquals('Max Musterman', $persons[0]->getName());
        $this->assertInstanceOf(TestCar::class, $persons[0]->getCar());
        $this->assertEquals('BMW', $persons[0]->getCar()->getMarke());
        $this->assertNotEmpty($persons[0]->getCar()->kennung);
        $this->assertEquals('i8', $persons[0]->getCar()->kennung);
        $this->assertInstanceOf(TestPhone::class, $persons[0]->getPhone());
        $this->assertEquals('iPhone', $persons[0]->getPhone()->getName());
        $this->assertEquals(9, $persons[0]->getPhone()->getValue());
        $this->assertEquals('Hamburg', $persons[0]->getBirthplace());
        $this->assertInstanceOf(TestAddress::class, $persons[0]->getAddress());
        $this->assertEquals('Hauptstraße 1', $persons[0]->getAddress()->getStreet());
        $this->assertEquals(245698, $persons[0]->getAddress()->getPlz());

        $this->assertInstanceOf(TestPerson::class, $persons[1]);
        $this->assertEquals('Dr. Dolittle', $persons[1]->getName());
        $this->assertInstanceOf(TestCar::class, $persons[1]->getCar());
        $this->assertEquals('Audi', $persons[1]->getCar()->getMarke());
        $this->assertNotEmpty($persons[0]->getCar()->kennung);
        $this->assertEquals('A3', $persons[1]->getCar()->kennung);
        $this->assertInstanceOf(TestPhone::class, $persons[1]->getPhone());
        $this->assertEquals('Sony', $persons[1]->getPhone()->getName());
        $this->assertEquals('Xperia Z3', $persons[1]->getPhone()->getValue());
        $this->assertEquals('München', $persons[1]->getBirthplace());
        $this->assertInstanceOf(TestAddress::class, $persons[1]->getAddress());
        $this->assertEquals('Partkstraße', $persons[1]->getAddress()->getStreet());
        $this->assertEquals(365494, $persons[1]->getAddress()->getPlz());
    }

    public function testWithoutFirstObject()
    {
        $doc = new DOMDocument();
        $doc->loadXml('<root><Car marke="Mercedes" /></root>');

        $mapper = new ClassMapper(
            [
                'Car' => TestCar::class
            ]
        );

        $hydrator = new Hydrator($mapper);
        /** @var TestCar $car */
        $car = $hydrator->hydrateDocument($doc);

        $this->assertInstanceOf(TestCar::class, $car);
        $this->assertEquals('Mercedes', $car->getMarke());
    }

    public function testWithLowerCaseClassMap()
    {
        //        $this->markTestSkipped();
        $doc = new DOMDocument();
        $doc->loadXml('<root><Car marke="Mercedes" /></root>');

        $mapper = new ClassMapper(
            [
                'car' => TestCar::class
            ]
        );

        $hydrator = new Hydrator($mapper);
        /** @var TestCar $car */
        $car = $hydrator->hydrateDocument($doc);

        $this->assertInstanceOf(TestCar::class, $car);
        $this->assertEquals('Mercedes', $car->getMarke());
    }

    public function testWithFacadeMethod()
    {
        $doc = new DOMDocument();
        $doc->loadXml('<root><Person><birthday>14.08.1991</birthday></Person></root>');

        $mapper = new ClassMapper(
            [
                'person' => TestPerson::class
            ]
        );

        $hydrator = new Hydrator($mapper);
        /** @var TestPerson $person */
        $person = $hydrator->hydrateDocument($doc);

        $this->assertInstanceOf(TestPerson::class, $person);
        $this->assertEquals('14.08.1991', $person->getBirthday()->format('d.m.Y'));
    }

    public function testWithTagName()
    {
        $doc = new DOMDocument();
        $doc->loadXml('<root><Person><hobby>Radeln</hobby></Person></root>');

        $mapper = new ClassMapper(
            [
                'person' => TestPerson::class,
                'hobby'  => TestHobby::class
            ]
        );

        $hydrator = new Hydrator($mapper);
        /** @var TestPerson $person */
        $person = $hydrator->hydrateDocument($doc);

        $this->assertInstanceOf(TestPerson::class, $person);
        $this->assertInstanceOf(TestHobby::class, $person->hobby);
        $this->assertEquals('Radeln', $person->hobby->value);
    }

    public function testPropertyAssignment()
    {
        $doc = new DOMDocument();
        $doc->load(__DIR__ . '/xml/test2.xml');

        $mapper = new ClassMapper(
            [
                'Stammdaten' => TestStammdaten::class
            ]
        );

        $hydrator = new Hydrator($mapper);
        /** @var TestStammdaten $stammdaten */
        $stammdaten = $hydrator->hydrateDocument($doc);

        $this->assertInstanceOf(TestStammdaten::class, $stammdaten);
        $this->assertEquals('Muster', $stammdaten->Name);
        $this->assertEquals('Max', $stammdaten->Vorname);
    }

    public function testFault()
    {
        $doc = new DOMDocument();
        $doc->load(__DIR__ . '/xml/fault.xml');

        $mapper = new ClassMapper(
            [
                'Envelope' => TestEnvelope::class,
                'Body'     => TestBody::class,
                'Fault'    => TestFault::class
            ]
        );

        $hydrator = new Hydrator($mapper);
        /** @var TestEnvelope $envelope */
        $envelope = $hydrator->hydrateDocument($doc);

        $this->assertInstanceOf(TestEnvelope::class, $envelope);
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
                'Envelope' => TestEnvelope::class,
                'Body'     => TestBody::class,
                'Fault'    => TestFault::class,
                'Result'   => TestResult::class,
                'Ort'      => TestOrt::class,
                'Oteil'    => TestOrtsTeil::class,
                'Strassen' => TestStrassen::class
            ]
        );

        $hydrator = new Hydrator($mapper);
        /** @var TestEnvelope $envelope */
        $envelope = $hydrator->hydrateDocument($doc);

        $this->assertInstanceOf(TestEnvelope::class, $envelope);
        $this->assertFalse($envelope->getBody()->hasFault());
        $this->assertInstanceOf(TestResult::class, $envelope->getBody()->getResult());
        $this->assertCount(1, $envelope->getBody()->getResult()->getOrte());
        $this->assertEquals('Hamburg', $envelope->getBody()->getResult()->getOrte()[0]->getName());
        $this->assertCount(4, $envelope->getBody()->getResult()->getOrte()[0]->getOrtsteile());
        $this->assertEquals('Hamburg-Altstadt', $envelope->getBody()->getResult()->getOrte()[0]->getOrtsteile()[1]->getName());

        for ($i = 0; $i < 4; $i++) {
            $this->assertNotEmpty($envelope->getBody()->getResult()->getOrte()[0]->getOrtsteile()[$i]->getStrassen());
        }
    }

    public function testFailedPropertyAssignment()
    {
        $doc = new DOMDocument();
        $doc->load(__DIR__ . '/xml/test2.xml');

        $mapper   = new ClassMapper(['mandant' => TestPerson::class]);
        $hydrator = new Hydrator($mapper);
        $hydrator->hydrateDocument($doc);

        $this->assertTrue($hydrator->hasWarnings());
        $this->assertNotEmpty($hydrator->getWarnings());
        $this->assertEquals(
            [
                ['Could not assign Element: Stammdaten']
            ],
            $hydrator->getWarnings()
        );

        $mapper   = new ClassMapper();
        $hydrator = new Hydrator($mapper);
        $hydrator->hydrateDocument($doc);

        $this->assertTrue($hydrator->hasWarnings());
        $this->assertNotEmpty($hydrator->getWarnings());
        $this->assertEquals(
            [
                ['Class not found: Mandant', 'Could not hydrate: Stammdaten'],
                'Could not hydrate: Mandant'
            ],
            $hydrator->getWarnings()
        );
    }
}