<?php

use Dgame\Soap\Bipro\BiProVersion;
use Dgame\Soap\Bipro\RequestSecurityToken;
use Dgame\Soap\Bipro\UsernameToken;
use Dgame\Soap\Body;
use Dgame\Soap\Envelope;
use Dgame\Soap\Header;
use Dgame\Soap\Security;

require_once 'vendor/autoload.php';

$envelope = new Envelope();

$security = new Security();
$token    = new UsernameToken('Foo', 'Bar');
$security->appendNode($token);

$header = new Header();
$header->appendNode($security);

$rst  = new RequestSecurityToken(new BiProVersion('2.1.6.1.1'));
$body = new Body();
$body->appendNode($rst);

$envelope->appendNode($header);
$envelope->appendNode($body);

print '<pre>';
print htmlentities($envelope->assemble()->saveXML());