<?php
namespace HtmlParser\Tests\TreeConstructor;

use HtmlParser\Tokenizer\Structs\AttributeStruct;
use HtmlParser\Tokenizer\Tokens\StartTagToken;
use HtmlParser\TreeConstructor\TreeConstructor;
use PHPUnit\Framework\TestCase;

class TreeConstructorTest extends TestCase
{
    public function testCreatingAnElementFromTagName()
    {
        $treeConstructor = new TreeConstructor();
        $htmlElement = $treeConstructor->createElementFromTagName('html');

        $this->assertInstanceOf('HtmlParser\TreeConstructor\Nodes\ElementNode', $htmlElement);
        $this->assertEquals('html', $htmlElement->getName());
        $this->assertCount(0, $htmlElement->getAttributes());
    }

    public function testGetElementFromToken()
    {
        $treeConstructor = new TreeConstructor();

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

        $inputElement = $treeConstructor->createElementFromToken($startTagToken);

        $this->assertInstanceOf('HtmlParser\TreeConstructor\Nodes\ElementNode', $inputElement);
        $this->assertEquals('input', $inputElement->getName());
        $this->assertCount(2, $inputElement->getAttributes());
    }
}
