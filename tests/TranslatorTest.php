<?php

namespace Dgame\Soap\Test;

use Dgame\Soap\Dom\Translator;
use Dgame\Soap\XmlElement;
use Dgame\Soap\XmlNode;
use DOMDocument;
use PHPUnit\Framework\TestCase;

/**
 * Class TranslatorTest
 * @package Dgame\Soap\Test
 */
final class TranslatorTest extends TestCase
{
    public function testXmlElementTranslation()
    {
        $doc        = new DOMDocument('1.0');
        $translator = new Translator();

        $node = $translator->translateNode($doc->createElement('test', 'foobar'));

        $this->assertNotNull($node);
        $this->assertInstanceOf(XmlElement::class, $node);
        $this->assertEquals('test', $node->getName());
        $this->assertTrue($node->hasValue());
        $this->assertFalse($node->hasAttributes());
        $this->assertEquals('test', $node->getName());
        $this->assertEquals('foobar', $node->getValue());

        $node = $translator->translateNode($doc->createElement('test', '42'));

        $this->assertNotNull($node);
        $this->assertInstanceOf(XmlElement::class, $node);
        $this->assertEquals('test', $node->getName());
        $this->assertTrue($node->hasValue());
        $this->assertFalse($node->hasAttributes());
        $this->assertEquals('test', $node->getName());
        $this->assertEquals(42, $node->getValue());

        $element = $doc->createElementNs('http://www.example.com/bar', 'bar:test', 'foobar');
        $element->setAttribute('id', '0');

        $node = $translator->translateNode($element);

        $this->assertNotNull($node);
        $this->assertInstanceOf(XmlElement::class, $node);
        $this->assertEquals('test', $node->getName());
        $this->assertTrue($node->hasValue());
        $this->assertTrue($node->hasAttributes());
        $this->assertTrue($node->hasPrefix());
        $this->assertCount(1, $node->getAttributes());
        $this->assertEquals('bar', $node->getPrefix());
        $this->assertEquals('test', $node->getName());
        $this->assertEquals('foobar', $node->getValue());
        $this->assertEquals('id', $node->getAttributes()[0]->getName());
        $this->assertEquals(0, $node->getAttributes()[0]->getValue());
    }

    public function testXmlNodeTranslation()
    {
        $doc        = new DOMDocument('1.0');
        $translator = new Translator();

        $element = $doc->createElementNs('http://www.example.com/abc', 'abc:test');
        $element->setAttribute('id', '0');
        $element->appendChild($doc->createElement('name', 'Franz'));
        $element->appendChild($doc->createElement('age', '42'));

        $node = $translator->translateNode($element);

        $this->assertNotNull($node);
        $this->assertInstanceOf(XmlNode::class, $node);
        $this->assertEquals('test', $node->getName());
        $this->assertFalse($node->hasValue());
        $this->assertTrue($node->hasAttributes());
        $this->assertTrue($node->hasChildren());
        $this->assertTrue($node->hasPrefix());
        $this->assertEquals('abc', $node->getPrefix());
        $this->assertEquals('test', $node->getName());
        $this->assertFalse($node->hasValue());
        $this->assertCount(1, $node->getAttributes());
        $this->assertEquals('id', $node->getAttributes()[0]->getName());
        $this->assertEquals(0, $node->getAttributes()[0]->getValue());
        $this->assertInstanceOf(XmlElement::class, $node->getChildren()[0]);
        $this->assertInstanceOf(XmlElement::class, $node->getChildren()[1]);
        $this->assertCount(2, $node->getChildren());
        $this->assertEquals('name', $node->getChildren()[0]->getName());
        $this->assertEquals('Franz', $node->getChildren()[0]->getValue());
        $this->assertEquals('age', $node->getChildren()[1]->getName());
        $this->assertEquals(42, $node->getChildren()[1]->getValue());
    }

    public function testDocumentTranslation()
    {
        $doc = new DOMDocument('1.0');
        $doc->load(__DIR__ . '/xml/test1.xml');

        $translator = new Translator();
        $elements   = $translator->translateDocument($doc);

        $this->assertCount(1, $elements);
        $this->assertEquals('soap-env', $elements[0]->getName());
        $this->assertCount(2, $elements[0]->getChildren());

        $child1 = $elements[0]->getChildren()[0];
        $child2 = $elements[0]->getChildren()[1];

        $this->assertInstanceOf(XmlNode::class, $child1);
        $this->assertInstanceOf(XmlNode::class, $child2);

        $this->assertEquals('person', $child1->getName());
        $this->assertTrue($child1->hasChildren());
        $this->assertCount(4, $child1->getChildren());
        $this->assertTrue($child1->hasAttributes());
        $this->assertCount(1, $child1->getAttributes());

        $this->assertEquals('name', $child1->getAttributes()[0]->getName());
        $this->assertEquals('Max Musterman', $child1->getAttributes()[0]->getValue());

        $this->assertInstanceOf(XmlElement::class, $child1->getChildren()[0]);
        $this->assertEquals('car', $child1->getChildren()[0]->getName());
        $this->assertFalse($child1->getChildren()[0]->hasValue());
        $this->assertCount(2, $child1->getChildren()[0]->getAttributes());
        $this->assertEquals('marke', $child1->getChildren()[0]->getAttributes()[0]->getName());
        $this->assertEquals('BMW', $child1->getChildren()[0]->getAttributes()[0]->getValue());

        $this->assertInstanceOf(XmlElement::class, $child1->getChildren()[1]);
        $this->assertEquals('phone', $child1->getChildren()[1]->getName());
        $this->assertEquals(9, $child1->getChildren()[1]->getValue());
        $this->assertCount(1, $child1->getChildren()[1]->getAttributes());
        $this->assertEquals('name', $child1->getChildren()[1]->getAttributes()[0]->getName());
        $this->assertEquals('iPhone', $child1->getChildren()[1]->getAttributes()[0]->getValue());

        $this->assertInstanceOf(XmlElement::class, $child1->getChildren()[2]);
        $this->assertEquals('birth-place', $child1->getChildren()[2]->getName());
        $this->assertEquals('Hamburg', $child1->getChildren()[2]->getValue());
        $this->assertFalse($child1->getChildren()[2]->hasAttributes());

        $this->assertInstanceOf(XmlNode::class, $child1->getChildren()[3]);
        $this->assertEquals('address', $child1->getChildren()[3]->getName());
        $this->assertFalse($child1->getChildren()[3]->hasValue());
        $this->assertFalse($child1->getChildren()[3]->hasAttributes());
        $this->assertCount(2, $child1->getChildren()[3]->getChildren());

        $i = 0;
        foreach (['street' => 'Hauptstraße 1', 'plz' => '245698'] as $name => $value) {
            $this->assertEquals($name, $child1->getChildren()[3]->getChildren()[$i]->getName());
            $this->assertEquals($value, $child1->getChildren()[3]->getChildren()[$i]->getValue());

            $i++;
        }

        $this->assertEquals('person', $child2->getName());
        $this->assertTrue($child2->hasAttributes());
        $this->assertCount(1, $child2->getAttributes());
        $this->assertTrue($child2->hasChildren());
        $this->assertCount(4, $child2->getChildren());

        $this->assertEquals('name', $child2->getAttributes()[0]->getName());
        $this->assertEquals('Dr. Dolittle', $child2->getAttributes()[0]->getValue());

        $this->assertInstanceOf(XmlElement::class, $child2->getChildren()[0]);
        $this->assertEquals('car', $child2->getChildren()[0]->getName());
        $this->assertFalse($child2->getChildren()[0]->hasValue());
        $this->assertCount(2, $child2->getChildren()[0]->getAttributes());
        $this->assertEquals('marke', $child2->getChildren()[0]->getAttributes()[0]->getName());
        $this->assertEquals('Audi', $child2->getChildren()[0]->getAttributes()[0]->getValue());

        $this->assertInstanceOf(XmlElement::class, $child2->getChildren()[1]);
        $this->assertEquals('phone', $child2->getChildren()[1]->getName());
        $this->assertEquals('Xperia Z3', $child2->getChildren()[1]->getValue());
        $this->assertCount(1, $child2->getChildren()[1]->getAttributes());
        $this->assertEquals('name', $child2->getChildren()[1]->getAttributes()[0]->getName());
        $this->assertEquals('Sony', $child2->getChildren()[1]->getAttributes()[0]->getValue());

        $this->assertInstanceOf(XmlElement::class, $child2->getChildren()[2]);
        $this->assertEquals('birth-place', $child2->getChildren()[2]->getName());
        $this->assertEquals('München', $child2->getChildren()[2]->getValue());
        $this->assertFalse($child2->getChildren()[2]->hasAttributes());

        $this->assertInstanceOf(XmlNode::class, $child2->getChildren()[3]);
        $this->assertEquals('address', $child2->getChildren()[3]->getName());
        $this->assertFalse($child2->getChildren()[3]->hasValue());
        $this->assertFalse($child2->getChildren()[3]->hasAttributes());
        $this->assertCount(2, $child2->getChildren()[3]->getChildren());

        $i = 0;
        foreach (['street' => 'Partkstraße', 'plz' => '365494'] as $name => $value) {
            $this->assertEquals($name, $child2->getChildren()[3]->getChildren()[$i]->getName());
            $this->assertEquals($value, $child2->getChildren()[3]->getChildren()[$i]->getValue());

            $i++;
        }
    }
}