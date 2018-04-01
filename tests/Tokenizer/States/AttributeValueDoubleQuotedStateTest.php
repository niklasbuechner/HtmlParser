<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\AttributeValueDoubleQuotedState;
use HtmlParser\Tokenizer\Structs\AttributeStruct;
use HtmlParser\Tokenizer\Tokens\StartTagToken;
use PHPUnit\Framework\TestCase;

class AttributeValueDoubleQuotedStateTest extends TestCase
{
    public function testAddCharactersToAttributeValue()
    {
        $tagToken = new StartTagToken();
        $tagToken->setCurrentAttribute(new AttributeStruct());

        $tokenizer = new TestTokenizer();
        $tokenizer->setCurrentToken($tagToken);

        $attributeValueDoubleQuotedState = new AttributeValueDoubleQuotedState();

        $attributeValueDoubleQuotedState->processCharacter('h', $tokenizer);
        $attributeValueDoubleQuotedState->processCharacter('e', $tokenizer);
        $attributeValueDoubleQuotedState->processCharacter('l', $tokenizer);
        $attributeValueDoubleQuotedState->processCharacter('l', $tokenizer);
        $attributeValueDoubleQuotedState->processCharacter('o', $tokenizer);
        $attributeValueDoubleQuotedState->processCharacter(' ', $tokenizer);
        $attributeValueDoubleQuotedState->processCharacter('w', $tokenizer);
        $attributeValueDoubleQuotedState->processCharacter('o', $tokenizer);
        $attributeValueDoubleQuotedState->processCharacter('r', $tokenizer);
        $attributeValueDoubleQuotedState->processCharacter('l', $tokenizer);
        $attributeValueDoubleQuotedState->processCharacter('d', $tokenizer);

        $this->assertEquals('hello world', $tagToken->getCurrentAttribute()->getValue());
    }

    public function testNamedCharacterReferenceInAttributeValue()
    {
        $tagToken = new StartTagToken();
        $tagToken->setCurrentAttribute(new AttributeStruct());

        $attributeValueDoubleQuotedState = new AttributeValueDoubleQuotedState();

        $tokenizer = new TestTokenizer();
        $tokenizer->setCurrentToken($tagToken);
        $tokenizer->setState($attributeValueDoubleQuotedState);

        // Careful, the state changes during the character processing.
        // It is set on the tokenizer.
        $tokenizer->getState()->processCharacter('&', $tokenizer);
        $tokenizer->getState()->processCharacter('u', $tokenizer);
        $tokenizer->getState()->processCharacter('u', $tokenizer);
        $tokenizer->getState()->processCharacter('m', $tokenizer);
        $tokenizer->getState()->processCharacter('l', $tokenizer);
        $tokenizer->getState()->processCharacter(';', $tokenizer);

        $this->assertEquals('ü', $tagToken->getCurrentAttribute()->getValue());
    }

    public function testClosingAttributeValue()
    {
        $attributeValueDoubleQuotedState = new AttributeValueDoubleQuotedState();
        $tokenizer = new TestTokenizer();

        // Careful, the state changes during the character processing.
        // It is set on the tokenizer.
        $attributeValueDoubleQuotedState->processCharacter('"', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\AfterAttributeValueQuotedState', $tokenizer->getState());
    }
}
