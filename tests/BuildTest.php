<?php

namespace Dgame\Soap\Test;

use Dgame\Soap\Translator\BuiltinToPackageTranslator;
use Dgame\Soap\Translator\PackageToBuiltinTranslator;
use DOMDocument;
use PHPUnit\Framework\TestCase;

/**
 * Class BuiltTest
 * @package Dgame\Soap\Test
 */
final class BuildTest extends TestCase
{
    public function testReBuild(): void
    {
        $package = new BuiltinToPackageTranslator();
        $builtin = new PackageToBuiltinTranslator();

        $doc1 = new DOMDocument();
        $this->assertTrue($doc1->load(__DIR__ . '/resources/soap_1.xml'));

        $start = time();
        $node  = $package->translate($doc1);
        $end   = time();

        $this->assertTrue(($end - $start) < 2);

        $doc2 = $builtin->translate($node);

        $this->assertXmlStringEqualsXmlString($doc1->saveXML(), $doc2->saveXML());
    }
}
