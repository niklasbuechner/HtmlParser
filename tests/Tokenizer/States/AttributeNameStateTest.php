<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\AttributeNameState;
use HtmlParser\Tokenizer\States\BeforeAttributeValueState;
use HtmlParser\Tokenizer\Structs\AttributeStruct;
use HtmlParser\Tokenizer\Tokens\StartTagToken;
use PHPUnit\Framework\TestCase;

class AttributeNameStateTest extends TestCase
{
    public function testAsciiAttributeName()
    {
        $tagToken = new StartTagToken();
        $tagToken->addAttribute(new AttributeStruct());

        $tokenizer = new TestTokenizer();
        $tokenizer->setToken($tagToken);

        $attributeNameState = new AttributeNameState();

        $attributeNameState->processCharacter('h', $tokenizer);
        $attributeNameState->processCharacter('r', $tokenizer);
        $attributeNameState->processCharacter('e', $tokenizer);
        $attributeNameState->processCharacter('f', $tokenizer);

        $attribute = $tokenizer->getToken()->getCurrentAttribute();
        $this->assertEquals('href', $attribute->getName());
    }

    public function testAttributeNameEndingInEqualsSign()
    {
        $tagToken = new StartTagToken();
        $tagToken->addAttribute(new AttributeStruct());

        $tokenizer = new TestTokenizer();
        $tokenizer->setToken($tagToken);

        $attributeNameState = new AttributeNameState();

        $attributeNameState->processCharacter('s', $tokenizer);
        $attributeNameState->processCharacter('r', $tokenizer);
        $attributeNameState->processCharacter('c', $tokenizer);
        $attributeNameState->processCharacter('=', $tokenizer);

        $attribute = $tokenizer->getToken()->getCurrentAttribute();
        $this->assertEquals('src', $attribute->getName());
        $this->assertInstanceOf('HtmlParser\Tokenizer\States\BeforeAttributeValueState', $tokenizer->getState());
    }

    public function testWhiteSpaceAfterAttributeName()
    {
        $tokenizer = new TestTokenizer();
        $attributeNameState = new AttributeNameState();

        $attributeNameState->processCharacter(' ', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\AfterAttributeNameState', $tokenizer->getState());
    }

    public function testStraySolidus()
    {
        $tokenizer = new TestTokenizer();
        $attributeNameState = new AttributeNameState();

        $attributeNameState->processCharacter('/', $tokenizer);

        // State is changed within the AfterAttributeNameState again.
        $this->assertInstanceOf('HtmlParser\Tokenizer\States\SelfClosingStartTagState', $tokenizer->getState());
    }

    public function testTagEnd()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new StartTagToken());

        $attributeNameState = new AttributeNameState();
        $attributeNameState->processCharacter('>', $tokenizer);

        // State is changed in the AfterAttributeNameState again.
        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DataState', $tokenizer->getState());
    }
}
