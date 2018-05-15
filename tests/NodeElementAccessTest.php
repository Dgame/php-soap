<?php

namespace Dgame\Soap\Test;

use Dgame\Soap\Element\XmlElement;
use Dgame\Soap\Element\XmlNode;
use Dgame\Soap\Element\XmlNodeInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class NodeElementAccessTest
 * @package Dgame\Soap\Test
 */
final class NodeElementAccessTest extends TestCase
{
    public function testHasElementWithName(): void
    {
        $node = new XmlNode('Foo');
        $this->assertFalse($node->hasElementWithName('Bar'));
        $node->appendElement(new XmlElement('Bar'));
        $this->assertTrue($node->hasElementWithName('Bar'));

        $this->assertFalse($node->hasElementWithName('Quatz'));
        $node->appendElement(new XmlElement('Quatz', null, 'soap'));
        $this->assertTrue($node->hasElementWithName('Quatz'));

        $this->assertTrue($node->hasElementWithName('Quatz', $element));
        $this->assertNotNull($element);
    }

    public function testGetElementByName(): void
    {
        $node = new XmlNode('Foo');
        $this->assertNull($node->getElementByName('Bar'));
        $node->appendElement(new XmlElement('Bar'));
        $this->assertNotNull($node->getElementByName('Bar'));

        $this->assertNull($node->getElementByName('Quatz'));
        $node->appendElement(new XmlElement('Quatz', null, 'soap'));
        $this->assertNotNull($node->getElementByName('Quatz'));
    }

    public function testGetOrSetElementByName(): void
    {
        $node = new XmlNode('Foo');
        $this->assertNotNull($node->getOrSetElementByName('Bar'));
        $this->assertNotNull($node->getElementByName('Bar'));
        $this->assertNotNull($node->getOrSetElementByName('Quatz', function (string $name): XmlNode {
            return new XmlNode($name, null, 'soap');
        }));
        $this->assertNotNull($node->getElementByName('Quatz'));
        $this->assertInstanceOf(XmlNode::class, $node->getElementByName('Quatz'));

        /** @var XmlNodeInterface $childNode */
        $childNode = $node->getElementByName('Quatz');
        $this->assertEquals('soap', $childNode->getPrefix());
    }
}
