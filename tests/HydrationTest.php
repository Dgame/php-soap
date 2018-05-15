<?php

namespace Dgame\Soap\Test;

use Dgame\Soap\Element\ElementInterface;
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
        $this->assertTrue($doc->load(__DIR__ . '/resources/test.xml'));

        $strategy = new DefaultHydratorStrategy();
        $strategy->setCallback('Envelope.Body.Fault.faultstring', function (ElementInterface $element) {
            return (object) ['fault' => $element->getValue()];
        });
        $hydator = new Hydrator($strategy);
        $hydator->hydrate($doc);

        $this->assertNotNull($strategy->top());
        $this->assertTrue(is_object($strategy->top()));
        $this->assertEquals('Es ist ein Fehler aufgetreten', $strategy->top()->fault);
    }

    public function testMultiHydration(): void
    {
        $doc = new DOMDocument('1.0', 'utf-8');
        $this->assertTrue($doc->load(__DIR__ . '/resources/test.xml'));

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

        $this->assertNotNull($strategy->top());
        $this->assertTrue(is_object($strategy->top()));
        $this->assertNotEmpty($strategy->top()->fault);
        $this->assertEquals('Fehler!', $strategy->top()->fault->faultcode);
        $this->assertEquals('Es ist ein Fehler aufgetreten', $strategy->top()->fault->faultstring);
    }
}
