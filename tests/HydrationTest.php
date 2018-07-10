<?php

namespace Dgame\Soap\Test;

use DateTime;
use Dgame\Soap\Element\ElementInterface;
use Dgame\Soap\Element\XmlElementInterface;
use Dgame\Soap\Element\XmlNodeInterface;
use Dgame\Soap\Hydrator\BindingHydratorStrategy;
use Dgame\Soap\Hydrator\DefaultHydratorStrategy;
use Dgame\Soap\Hydrator\Hydrator;
use DOMDocument;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Class HydrationTest
 * @package Dgame\Soap\Test
 */
final class HydrationTest extends TestCase
{
    public function testSingleHydration(): void
    {
        $doc = new DOMDocument('1.0', 'utf-8');
        $this->assertTrue($doc->load(__DIR__ . '/resources/fault.xml'));

        $strategy = new DefaultHydratorStrategy();
        $strategy->setCallback('Envelope.Body.Fault.faultstring', function (ElementInterface $element) {
            return (object) ['fault' => $element->getValue()];
        });
        $hydator = new Hydrator($strategy);
        $hydator->hydrate($doc);

        $this->assertNotNull($strategy->getHydrated());
        $this->assertTrue(is_object($strategy->getHydrated()));
        $this->assertEquals('Es ist ein Fehler aufgetreten', $strategy->getHydrated()->fault);
    }

    public function testMultiHydration(): void
    {
        $doc = new DOMDocument('1.0', 'utf-8');
        $this->assertTrue($doc->load(__DIR__ . '/resources/fault.xml'));

        $strategy = new DefaultHydratorStrategy();
        $strategy->setCallback('Envelope', function () {
            return new stdClass();
        });
        $strategy->setCallback('Envelope.Body.Fault', function (ElementInterface $element, stdClass $envelope) {
            $envelope->fault = new stdClass();

            return $envelope->fault;
        });
        $strategy->setCallback('Envelope.Body.Fault.faultcode', function (ElementInterface $element, stdClass $fault): void {
            $fault->faultcode = $element->getValue();
        });
        $strategy->setCallback('Envelope.Body.Fault.faultstring', function (ElementInterface $element, stdClass $fault): void {
            $fault->faultstring = $element->getValue();
        });
        $hydator = new Hydrator($strategy);
        $hydator->hydrate($doc);

        $this->assertNotNull($strategy->getHydrated());
        $this->assertTrue(is_object($strategy->getHydrated()));
        $this->assertNotEmpty($strategy->getHydrated()->fault);
        $this->assertEquals('Fehler!', $strategy->getHydrated()->fault->faultcode);
        $this->assertEquals('Es ist ein Fehler aufgetreten', $strategy->getHydrated()->fault->faultstring);
    }

    /**
     * @throws \ReflectionException
     */
    public function testBindingHydration(): void
    {
        $doc = new DOMDocument();
        $doc->load(__DIR__ . '/resources/test1.xml');

        $strategy = new BindingHydratorStrategy();
        $root     = $strategy->bind('*person', function (XmlElementInterface $person): void {
            $this->names[] = $person->getAttributeByName('name')->getValue();
        });
        $hydator  = new Hydrator($strategy);
        $hydator->hydrate($doc);

        $this->assertCount(2, $root->names);
        $this->assertEquals(['Max Musterman', 'Dr. Dolittle'], $root->names);
    }

    public function testFullObjectHydration(): void
    {
        $doc = new DOMDocument();
        $doc->load(__DIR__ . '/resources/test1.xml');

        $strategy = new DefaultHydratorStrategy();
        $strategy->setRewriteRule('/^(?:soap\-?)?env(?:elope)?(.+?)?$/iS', function (string $footprint, array $matches) {
            if (!empty($matches[1])) {
                return 'root' . $matches[1];
            }

            return 'root';
        });
        $strategy->setCallback('root', function () {
            return new stdClass();
        });
        $strategy->setCallback('root.person', function (ElementInterface $element, stdClass $root) {
            $person       = new stdClass();
            $person->name = $element->getAttributeByName('name')->getValue();

            $root->persons[] = $person;

            return $person;
        });
        $strategy->setCallback('root.person.car', function (ElementInterface $element, stdClass $person) {
            $car          = new stdClass();
            $car->marke   = $element->getAttributeByName('marke')->getValue();
            $car->kennung = $element->getAttributeByName('kennung')->getValue();

            $person->car = $car;

            return $car;
        });
        $strategy->setCallback('root.person.phone', function (ElementInterface $element, stdClass $person) {
            $phone        = new stdClass();
            $phone->marke = $element->getAttributeByName('name')->getValue();
            $phone->name  = $element->getValue();

            $person->phone = $phone;

            return $phone;
        });
        $strategy->setCallback('root.person.birth-place', function (ElementInterface $element, stdClass $person): void {
            $person->birthplace = $element->getValue();
        });
        $strategy->setCallback('root.person.address', function (XmlNodeInterface $node, stdClass $person) {
            $address         = new stdClass();
            $person->address = $address;

            return $address;
        });
        $strategy->setCallback('root.person.address.street', function (ElementInterface $element, stdClass $address): void {
            $address->street = $element->getValue();
        });
        $strategy->setCallback('root.person.address.plz', function (ElementInterface $element, stdClass $address): void {
            $address->plz = $element->getValue();
        });

        $hydator = new Hydrator($strategy);
        $hydator->hydrate($doc);

        $root = $strategy->getHydrated();

        $this->assertNotNull($root);
        $persons = $root->persons;
        $this->assertCount(2, $persons);
        $this->assertEquals('Max Musterman', $persons[0]->name);
        $this->assertEquals('BMW', $persons[0]->car->marke);
        $this->assertNotEmpty($persons[0]->car->kennung);
        $this->assertEquals('i8', $persons[0]->car->kennung);
        $this->assertEquals('iPhone', $persons[0]->phone->marke);
        $this->assertEquals(9, $persons[0]->phone->name);
        $this->assertEquals('Hamburg', $persons[0]->birthplace);
        $this->assertEquals('Hauptstraße 1', $persons[0]->address->street);
        $this->assertEquals(245698, $persons[0]->address->plz);
        $this->assertEquals('Dr. Dolittle', $persons[1]->name);
        $this->assertEquals('Audi', $persons[1]->car->marke);
        $this->assertNotEmpty($persons[0]->car->kennung);
        $this->assertEquals('A3', $persons[1]->car->kennung);
        $this->assertEquals('Sony', $persons[1]->phone->marke);
        $this->assertEquals('Xperia Z3', $persons[1]->phone->name);
        $this->assertEquals('München', $persons[1]->birthplace);
        $this->assertEquals('Partkstraße', $persons[1]->address->street);
        $this->assertEquals(365494, $persons[1]->address->plz);
    }

    public function testPartialHydratedObject(): void
    {
        $doc = new DOMDocument();
        $doc->loadXml('<root><Person><birthday>14.08.1991</birthday></Person></root>');

        $strategy = new DefaultHydratorStrategy();
        $strategy->setCallback('root.Person.birthday', function (ElementInterface $element) {
            $person = new stdClass();
            $person->birthday = new Datetime($element->getValue());

            return $person;
        });

        $hydator = new Hydrator($strategy);
        $hydator->hydrate($doc);

        $person = $strategy->getHydrated();

        $this->assertEquals('14.08.1991', $person->birthday->format('d.m.Y'));
    }
}
