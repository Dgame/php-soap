# php-soap

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Dgame/php-soap/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Dgame/php-soap/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/Dgame/php-soap/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Dgame/php-soap/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/Dgame/php-soap/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Dgame/php-soap/build-status/master)
[![StyleCI](https://styleci.io/repos/65689541/shield?branch=master)](https://styleci.io/repos/65689541)
[![Build Status](https://travis-ci.org/Dgame/php-soap.svg?branch=master)](https://travis-ci.org/Dgame/php-soap)

Hydrate XML-Documents to objects or build XML from objects.

# Hydration
To hydrate XML files, you just simply have to write corresponding objects with appropriate properties / methods.
Let's consider this xml file as an example:
```xml
<?xml version="1.0" encoding="utf-8" ?>
<soap-env>
    <person name="Max Musterman">
        <car marke="BMW" kennung="i8"/>
        <phone name="iPhone">9</phone>
        <birth-place>Hamburg</birth-place>
        <address>
            <street>Hauptstraße 1</street>
            <plz>245698</plz>
        </address>
    </person>
    <person name="Dr. Dolittle">
        <car marke="Audi" kennung="A3"/>
        <phone name="Sony">Xperia Z3</phone>
        <birth-place>München</birth-place>
        <address>
            <street>Partkstraße</street>
            <plz>365494</plz>
        </address>
    </person>
</soap-env>
```
To hydrate it, you need a `Root` or `Envelope` class, a `Person` class, a `Car` class, a `Phone` class and an `Address` class.
To find these objects, they should be named like the XML-Tags or you simply register them on the _ClassMapper_. To put the data into the objects, the methods should be named like the XML-Tag where the data comes from, prefixed with `set` or `append`. For example, to put the name into the person, these three variants are tried to fill the object:
 - Use a public property with name `name`
 - Use a public method: `setName`
 - Use a public method: `appendName`
 
 This is accomplished with the [php-object](https://github.com/Dgame/php-object) package.

But let's see the whole example of the hydration process:
```php
$doc = new DOMDocument();
$doc->loadXML(...);

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
$objects  = $hydrator->hydrateDocument($doc);
```

That's it. As you can see, with the line `$mapper->appendPattern('/^(?:soap\-?)?env(?:elope)?/iS', 'Root');` we apply a regex to match all variants of soap XML-Tags and determine that the `Root` class should be used. And now we can test the result:

```php
$this->assertCount(1, $objects);

/** @var TestRoot $root */
$root = $objects[0];

$this->assertNotNull($root);
$this->assertInstanceOf(TestRoot::class, $root);

$persons = $root->getPersons();
$this->assertCount(2, $persons);

$this->assertInstanceOf(TestPerson::class, $persons[0]);
$this->assertEquals('Max Musterman', $persons[0]->getName());
$this->assertInstanceOf(TestCar::class, $persons[0]->getCar());
$this->assertEquals('BMW', $persons[0]->getCar()->getMarke());
$this->assertNotEmpty($persons[0]->getCar()->kennung);
$this->assertEquals('i8', $persons[0]->getCar()->kennung);
$this->assertInstanceOf(TestPhone::class, $persons[0]->getPhone());
$this->assertEquals('iPhone', $persons[0]->getPhone()->getName());
$this->assertEquals(9, $persons[0]->getPhone()->getValue());
$this->assertEquals('Hamburg', $persons[0]->getBirthplace());
$this->assertInstanceOf(TestAddress::class, $persons[0]->getAddress());
$this->assertEquals('Hauptstraße 1', $persons[0]->getAddress()->getStreet());
$this->assertEquals(245698, $persons[0]->getAddress()->getPlz());

$this->assertInstanceOf(TestPerson::class, $persons[1]);
$this->assertEquals('Dr. Dolittle', $persons[1]->getName());
$this->assertInstanceOf(TestCar::class, $persons[1]->getCar());
$this->assertEquals('Audi', $persons[1]->getCar()->getMarke());
$this->assertNotEmpty($persons[0]->getCar()->kennung);
$this->assertEquals('A3', $persons[1]->getCar()->kennung);
$this->assertInstanceOf(TestPhone::class, $persons[1]->getPhone());
$this->assertEquals('Sony', $persons[1]->getPhone()->getName());
$this->assertEquals('Xperia Z3', $persons[1]->getPhone()->getValue());
$this->assertEquals('München', $persons[1]->getBirthplace());
$this->assertInstanceOf(TestAddress::class, $persons[1]->getAddress());
$this->assertEquals('Partkstraße', $persons[1]->getAddress()->getStreet());
$this->assertEquals(365494, $persons[1]->getAddress()->getPlz());
```

Pretty simple, isn't it?

# Dehydration
Of course you can dehydrate/(re)assemble hydrated elements:
```php
$doc2 = $hydrator->assemble($root);

$this->assertEqualXMLStructure($doc->documentElement, $doc2->documentElement);
```
That's all you need.

# Creation
And ultimately you can also create XML from your existing objects. Therefore, the following example shows four different BiPRO-Request:

### RequestTokenService       
```php        
$envelope = new Envelope();

$security = new Security();
$token    = new UsernameToken('Foo', 'Bar');
$security->appendElement($token);

$header = new Header();
$header->appendElement($security);

$rst = new RequestSecurityToken();
$rst->appendElement(new BiPROVersion('2.1.6.1.1'));

$body = new Body();
$body->appendElement($rst);

$envelope->appendElement($header);
$envelope->appendElement($body);

$assembler = new Assembler();
$envelope->accept($assembler);
      
print $assembler->getDocument()->saveXML();     
```       
results in        
```xml        
<?xml version="1.0" encoding="utf-8"?>        
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">        
  <soap:Header>       
    <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" soap:mustUnderstand="1">     
      <wsse:UsernameToken>        
        <wsse:Username>Foo</wsse:Username>        
        <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">Bar</wsse:Password>     
      </wsse:UsernameToken>       
    </wsse:Security>      
  </soap:Header>      
  <soap:Body>     
    <ns2:RequestSecurityToken xmlns="http://schemas.xmlsoap.org/ws/2004/08/addressing" xmlns:ns2="http://schemas.xmlsoap.org/ws/2005/02/trust" xmlns:ns3="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" xmlns:ns4="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:ns5="http://www.w3.org/2000/09/xmldsig#" xmlns:ns6="http://schemas.xmlsoap.org/ws/2004/09/policy">      
      <ns2:TokenType>http://schemas.xmlsoap.org/ws/2005/02/sc/sct</ns2:TokenType>     
      <ns2:RequestType>http://schemas.xmlsoap.org/ws/2005/02/trust/Issue</ns2:RequestType>        
      <allgemein:BiPROVersion xmlns:allgemein="http://www.bipro.net/namespace/allgemein">2.1.6.1.1</allgemein:BiPROVersion>       
    </ns2:RequestSecurityToken>       
  </soap:Body>        
</soap:Envelope>      
```       
      
### ListShipments     
```php        
$envelope = new Envelope();

$security = new Security();
$token    = new SecurityContextToken('bipro:7860072500822840554');
$security->appendElement($token);

$header = new Header();
$header->appendElement($security);

$request = new Request(new BiPROVersion('2.1.4.1.1'));
$request->setConfirm(false);
$shipment = new ListShipments($request);

$body = new Body();
$body->appendElement($shipment);

$envelope->appendElement($header);
$envelope->appendElement($body);

$assembler = new Assembler();
$envelope->accept($assembler);

print $assembler->getDocument()->saveXML();
```       
results in        
```xml        
<?xml version="1.0" encoding="utf-8"?>        
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">        
  <soap:Header>       
    <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" soap:mustUnderstand="1">     
      <wsc:SecurityContextToken xmlns:wsc="http://schemas.xmlsoap.org/ws/2005/02/sc">     
        <wsc:Identifier>bipro:7860072500822840554</wsc:Identifier>        
      </wsc:SecurityContextToken>     
    </wsse:Security>      
  </soap:Header>      
  <soap:Body>     
    <transfer:ListShipments xmlns:transfer="http://www.bipro.net/namespace/transfer">     
      <transfer:Request>      
        <allgemein:BiPROVersion xmlns:allgemein="http://www.bipro.net/namespace/allgemein">2.1.4.1.1</allgemein:BiPROVersion>     
        <transfer:BestaetigeLieferungen>false</transfer:BestaetigeLieferungen>        
      </transfer:Request>     
    </transfer:ListShipments>     
  </soap:Body>        
</soap:Envelope>      
```       
      
### GetShipment       
```php        
$envelope = new Envelope();

$security = new Security();
$token    = new SecurityContextToken('bipro:7860072500822840554');
$security->appendElement($token);

$header = new Header();
$header->appendElement($security);

$request = new Request(new BiPROVersion('2.1.4.1.1'));
$request->setConsumerId(1);
$request->setId(1);
$request->setConfirm(false);
$shipment = new GetShipment($request);

$body = new Body();
$body->appendElement($shipment);

$envelope->appendElement($header);
$envelope->appendElement($body);

$assembler = new Assembler();
$envelope->accept($assembler);

print $assembler->getDocument()->saveXML();
```       
results in        
```xml        
<?xml version="1.0" encoding="utf-8"?>        
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">        
  <soap:Header>       
    <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" soap:mustUnderstand="1">     
      <wsc:SecurityContextToken xmlns:wsc="http://schemas.xmlsoap.org/ws/2005/02/sc">     
        <wsc:Identifier>bipro:7860072500822840554</wsc:Identifier>        
      </wsc:SecurityContextToken>     
    </wsse:Security>      
  </soap:Header>      
  <soap:Body>     
    <transfer:GetShipment xmlns:transfer="http://www.bipro.net/namespace/transfer">       
      <transfer:Request>      
        <allgemein:BiPROVersion xmlns:allgemein="http://www.bipro.net/namespace/allgemein">2.1.4.1.1</allgemein:BiPROVersion>     
        <transfer:ConsumerID>1</transfer:ConsumerID>      
        <transfer:ID>1</transfer:ID>      
        <transfer:BestaetigeLieferungen>false</transfer:BestaetigeLieferungen>        
      </transfer:Request>     
    </transfer:GetShipment>       
  </soap:Body>        
</soap:Envelope>      
```

## AcknowledgeShipment 
```php
$envelope = new Envelope();

$security = new Security();
$token    = new SecurityContextToken('bipro:7860072500822840554');
$security->appendElement($token);

$header = new Header();
$header->appendElement($security);

$request = new Request(new BiPROVersion('2.1.4.1.1'));
$request->setConsumerId(1);
$request->setId(1);
$shipment = new AcknowledgeShipment($request);

$body = new Body();
$body->appendElement($shipment);

$envelope->appendElement($header);
$envelope->appendElement($body);

$assembler = new Assembler();
$envelope->accept($assembler);

print $assembler->getDocument()->saveXML();
```

results in

```xml
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
    <soap:Header>
        <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
            <wsc:SecurityContextToken xmlns:wsc="http://schemas.xmlsoap.org/ws/2005/02/sc">
                <wsc:Identifier>bipro:7860072500822840554</wsc:Identifier>
            </wsc:SecurityContextToken>
        </wsse:Security>
    </soap:Header>
    <soap:Body>
        <transfer:AcknowledgeShipment xmlns:transfer="http://www.bipro.net/namespace/transfer">
            <transfer:Request>
                <allgemein:BiPROVersion xmlns:allgemein="http://www.bipro.net/namespace/allgemein">2.1.4.1.1</allgemein:BiPROVersion>
                <transfer:ConsumerID>1</transfer:ConsumerID>
                <transfer:ID>1</transfer:ID>
            </transfer:Request>
        </transfer:AcknowledgeShipment>
    </soap:Body>
</soap:Envelope>
```
