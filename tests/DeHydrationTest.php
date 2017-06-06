<?php

namespace Dgame\Soap\Test;

use Dgame\Soap\Hydrator\ClassMapper;
use Dgame\Soap\Hydrator\Hydrator;
use Dgame\Soap\Test\Object\TestAddress;
use Dgame\Soap\Test\Object\TestCar;
use Dgame\Soap\Test\Object\TestPerson;
use Dgame\Soap\Test\Object\TestPhone;
use Dgame\Soap\Test\Object\TestRoot;
use DOMDocument;
use PHPUnit\Framework\TestCase;

/**
 * Class DeHydrationTest
 * @package Dgame\Soap\Test
 */
final class DeHydrationTest extends TestCase
{
    public function testDehydration()
    {
        $doc                     = new DOMDocument('1.0', 'utf-8');
        $doc->formatOutput       = false;
        $doc->preserveWhiteSpace = false;
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
        $root     = $hydrator->hydrate($doc);

        $this->assertNotNull($root);
        $this->assertInstanceOf(TestRoot::class, $root);

        $node = $hydrator->assemble($root);

        $this->assertEqualXMLStructure($doc->documentElement, $node->documentElement);
    }
}