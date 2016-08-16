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

require_once 'vendor/autoload.php';

print '<pre>';

$envelope = new Envelope();
$envelope->setNamespace('soapenv');

$security = new Security();
$token    = new UsernameToken('Foo', 'Bar');
$security->appendNode($token);

$header = new Header();
$header->appendNode($security);

$rst  = new RequestSecurityToken(new Version('2.1.6.1.1'));
$body = new Body();
$body->appendNode($rst);

$envelope->appendNode($header);
$envelope->appendNode($body);

print htmlentities($envelope->assemble()->saveXML());
print str_repeat('-', 50) . PHP_EOL;

$envelope = new Envelope();

$security = new Security();
$token    = new SecurityContextToken('bipro:7860072500822840554');
$security->appendNode($token);

$header = new Header();
$header->appendNode($security);

$request  = new Request(new Version('2.1.4.1.1'));
$shipment = new ListShipments($request);

$body = new Body();
$body->appendNode($shipment);

$envelope->appendNode($header);
$envelope->appendNode($body);

print htmlentities($envelope->assemble()->saveXML());
print str_repeat('-', 50) . PHP_EOL;

$envelope = new Envelope();

$security = new Security();
$token    = new SecurityContextToken('bipro:7860072500822840554');
$security->appendNode($token);

$header = new Header();
$header->appendNode($security);

$request = new Request(new Version('2.1.4.1.1'));
$request->setId(1);
$request->setConsumerId(2);
$shipment = new GetShipment($request);

$body = new Body();
$body->appendNode($shipment);

$envelope->appendNode($header);
$envelope->appendNode($body);

print htmlentities($envelope->assemble()->saveXML());