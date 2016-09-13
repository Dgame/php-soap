<?php

use Dgame\Soap\Component\Bipro\GetShipment;
use Dgame\Soap\Component\Bipro\ListShipments;
use Dgame\Soap\Component\Bipro\Request;
use Dgame\Soap\Component\Bipro\RequestSecurityToken;
use Dgame\Soap\Component\Bipro\SecurityContextToken;
use Dgame\Soap\Component\Bipro\UsernameToken;
use Dgame\Soap\Component\Bipro\Version;
use Dgame\Soap\Component\Body;
use Dgame\Soap\Component\Envelope;
use Dgame\Soap\Component\Header;
use Dgame\Soap\Component\Security;
use PHPUnit\Framework\TestCase;

require_once '../vendor/autoload.php';

class TestDocumentAssembler extends TestCase
{
    public function testLoginRequestOutput()
    {
        $envelope = new Envelope();

        $security = new Security();
        $token    = new UsernameToken('Foo', 'Bar');
        $security->appendChild($token);

        $header = new Header();
        $header->appendChild($security);

        $rst  = new RequestSecurityToken(new Version('2.1.6.1.1'));
        $body = new Body();
        $body->appendChild($rst);

        $envelope->appendChild($header);
        $envelope->appendChild($body);

        $d1                     = new DOMDocument('1.0', 'utf-8');
        $d1->formatOutput       = false;
        $d1->preserveWhiteSpace = false;
        $d1->load(getcwd() . '/xml/login_request.xml');

        $d2                     = new DOMDocument('1.0', 'utf-8');
        $d2->formatOutput       = false;
        $d2->preserveWhiteSpace = false;

        $envelope->assemble($d2);

        $this->assertEquals($d1->saveXML(), $d2->saveXML());
    }

    public function testListShipmentOutput()
    {
        $envelope = new Envelope();

        $security = new Security();
        $token    = new SecurityContextToken('bipro:7860072500822840554');
        $security->appendChild($token);

        $header = new Header();
        $header->appendChild($security);

        $request  = new Request(new Version('2.1.4.1.1'));
        $shipment = new ListShipments($request);

        $body = new Body();
        $body->appendChild($shipment);

        $envelope->appendChild($header);
        $envelope->appendChild($body);

        $d1                     = new DOMDocument('1.0', 'utf-8');
        $d1->formatOutput       = false;
        $d1->preserveWhiteSpace = false;
        $d1->load(getcwd() . '/xml/list_shipment.xml');

        $d2                     = new DOMDocument('1.0', 'utf-8');
        $d2->formatOutput       = false;
        $d2->preserveWhiteSpace = false;

        $envelope->assemble($d2);

        $this->assertEquals($d1->saveXML(), $d2->saveXML());
    }

    public function testGetShipmentOutput()
    {
        $envelope = new Envelope();

        $security = new Security();
        $token    = new SecurityContextToken('bipro:7860072500822840554');
        $security->appendChild($token);

        $header = new Header();
        $header->appendChild($security);

        $request = new Request(new Version('2.1.4.1.1'));
        $request->setId(1);
        $request->setConsumerId(2);
        $shipment = new GetShipment($request);

        $body = new Body();
        $body->appendChild($shipment);

        $envelope->appendChild($header);
        $envelope->appendChild($body);

        $d1                     = new DOMDocument('1.0', 'utf-8');
        $d1->formatOutput       = false;
        $d1->preserveWhiteSpace = false;
        $d1->load(getcwd() . '/xml/get_shipment.xml');

        $d2                     = new DOMDocument('1.0', 'utf-8');
        $d2->formatOutput       = false;
        $d2->preserveWhiteSpace = false;

        $envelope->assemble($d2);

        $this->assertEquals($d1->saveXML(), $d2->saveXML());
    }
}