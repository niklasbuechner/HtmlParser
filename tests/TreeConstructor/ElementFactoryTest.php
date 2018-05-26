<?php
namespace HtmlParser\Tests\TreeConstructor;

use HtmlParser\Tokenizer\Structs\AttributeStruct;
use HtmlParser\Tokenizer\Tokens\DoctypeToken;
use HtmlParser\Tokenizer\Tokens\StartTagToken;
use HtmlParser\TreeConstructor\ElementFactory;
use PHPUnit\Framework\TestCase;

class ElementFactoryTest extends TestCase
{
    public function testCreatingAnElementFromTagName()
    {
        $elementFactory = new ElementFactory();
        $htmlElement = $elementFactory->createElementFromTagName('html');

        $this->assertInstanceOf('HtmlParser\TreeConstructor\Nodes\ElementNode', $htmlElement);
        $this->assertEquals('html', $htmlElement->getName());
        $this->assertCount(0, $htmlElement->getAttributes());
    }

    public function testGetElementFromToken()
    {
        $elementFactory = new ElementFactory();

        $typeAttribute = new AttributeStruct();
        $typeAttribute->appendCharacterToAttributeName('type');
        $typeAttribute->appendCharacterToAttributeValue('text');
        $valueAttribute = new AttributeStruct();
        $valueAttribute->appendCharacterToAttributeName('value');
        $valueAttribute->appendCharacterToAttributeValue('Hello World');

        $startTagToken = new StartTagToken();
        $startTagToken->appendCharacterToName('input');
        $startTagToken->addAttribute($typeAttribute);
        $startTagToken->addAttribute($valueAttribute);
        $startTagToken->prepareEmit();

        $inputElement = $elementFactory->createElementFromToken($startTagToken);

        $this->assertInstanceOf('HtmlParser\TreeConstructor\Nodes\ElementNode', $inputElement);
        $this->assertEquals('input', $inputElement->getName());
        $this->assertCount(2, $inputElement->getAttributes());
    }

    public function testDoctypeWithoutQuirksMode()
    {
        $elementFactory = new ElementFactory();

        $doctypeToken = new DoctypeToken();
        $doctypeToken->appendCharacterToName('html');

        $doctypeNode = $elementFactory->createDoctypeFromToken($doctypeToken);

        $this->assertFalse($doctypeNode->getQuirksMode());
        $this->assertFalse($doctypeNode->getLimitedQuirksMode());
    }

    public function testDoctypeWithQuirksMode()
    {
        $elementFactory = new ElementFactory();

        $doctypeToken = new DoctypeToken();
        $doctypeToken->appendCharacterToName('HTML');
        $doctypeToken->appendCharacterToPublicIdentifier('-//W3C//DTD HTML 3.2 Final//EN');
        $doctypeToken->appendCharacterToSystemIdentifier('http://www.w3.org/TR/html4/strict.dtd');

        $doctypeNode = $elementFactory->createDoctypeFromToken($doctypeToken);

        $this->assertTrue($doctypeNode->getQuirksMode());
        $this->assertFalse($doctypeNode->getLimitedQuirksMode());
    }

    public function testDoctypeWithLimitedQuirksMode()
    {
        $elementFactory = new ElementFactory();

        $doctypeToken = new DoctypeToken();
        $doctypeToken->appendCharacterToName('HTML');
        $doctypeToken->appendCharacterToPublicIdentifier('-//W3C//DTD XHTML 1.0 Frameset//');
        $doctypeToken->appendCharacterToSystemIdentifier('http://www.w3.org/TR/html4/strict.dtd');

        $doctypeNode = $elementFactory->createDoctypeFromToken($doctypeToken);

        $this->assertFalse($doctypeNode->getQuirksMode());
        $this->assertTrue($doctypeNode->getLimitedQuirksMode());
    }
}
