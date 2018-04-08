<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\AttributeValueSingleQuotedState;
use HtmlParser\Tokenizer\Structs\AttributeStruct;
use HtmlParser\Tokenizer\Tokens\StartTagToken;
use PHPUnit\Framework\TestCase;

class AttributeValueSingleQuotedStateTest extends TestCase
{
    public function testAddCharactersToAttributeValue()
    {
        $tagToken = new StartTagToken();
        $tagToken->addAttribute(new AttributeStruct());

        $tokenizer = new TestTokenizer();
        $tokenizer->setToken($tagToken);

        $attributeValueSingleQuotedState = new AttributeValueSingleQuotedState();

        $attributeValueSingleQuotedState->processCharacter('h', $tokenizer);
        $attributeValueSingleQuotedState->processCharacter('e', $tokenizer);
        $attributeValueSingleQuotedState->processCharacter('l', $tokenizer);
        $attributeValueSingleQuotedState->processCharacter('l', $tokenizer);
        $attributeValueSingleQuotedState->processCharacter('o', $tokenizer);
        $attributeValueSingleQuotedState->processCharacter(' ', $tokenizer);
        $attributeValueSingleQuotedState->processCharacter('w', $tokenizer);
        $attributeValueSingleQuotedState->processCharacter('o', $tokenizer);
        $attributeValueSingleQuotedState->processCharacter('r', $tokenizer);
        $attributeValueSingleQuotedState->processCharacter('l', $tokenizer);
        $attributeValueSingleQuotedState->processCharacter('d', $tokenizer);

        $this->assertEquals('hello world', $tagToken->getCurrentAttribute()->getValue());
    }

    public function testNamedCharacterReferenceInAttributeValue()
    {
        $tagToken = new StartTagToken();
        $tagToken->addAttribute(new AttributeStruct());

        $attributeValueSingleQuotedState = new AttributeValueSingleQuotedState();

        $tokenizer = new TestTokenizer();
        $tokenizer->setToken($tagToken);
        $tokenizer->setState($attributeValueSingleQuotedState);

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
        $attributeValueSingleQuotedState = new AttributeValueSingleQuotedState();
        $tokenizer = new TestTokenizer();

        // Careful, the state changes during the character processing.
        // It is set on the tokenizer.
        $attributeValueSingleQuotedState->processCharacter('\'', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\AfterAttributeValueQuotedState', $tokenizer->getState());
    }
}
