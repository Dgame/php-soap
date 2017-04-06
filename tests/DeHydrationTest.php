<?php

namespace Dgame\Soap\Test;

use Dgame\Soap\Hydrator\ClassMapper;
use Dgame\Soap\Hydrator\Dom\Hydrator;
use Dgame\Soap\Test\Object\Address;
use Dgame\Soap\Test\Object\Car;
use Dgame\Soap\Test\Object\Person;
use Dgame\Soap\Test\Object\Phone;
use Dgame\Soap\Test\Object\Root;
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

        $doc2 = new DOMDocument('1.0', 'utf-8');
        $hydrator->assemble($root, $doc2);

        $xml = preg_replace('#<([^>]+)(\s*[^>]*)></\1>#', '<$1$2/>', $doc2->saveXml());
        $this->assertEquals($doc->saveXML(), $xml);
    }
}