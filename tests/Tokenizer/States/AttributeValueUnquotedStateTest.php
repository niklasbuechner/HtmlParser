<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\AttributeValueUnquotedState;
use HtmlParser\Tokenizer\Structs\AttributeStruct;
use HtmlParser\Tokenizer\Tokens\StartTagToken;
use PHPUnit\Framework\TestCase;

class AttributeValueUnquotedStateTest extends TestCase
{
    public function testAddCharactersToAttributeValue()
    {
        $tagToken = new StartTagToken();
        $tagToken->addAttribute(new AttributeStruct());

        $tokenizer = new TestTokenizer();
        $tokenizer->setToken($tagToken);

        $attributeValueUnquotedState = new AttributeValueUnquotedState();

        $attributeValueUnquotedState->processCharacter('h', $tokenizer);
        $attributeValueUnquotedState->processCharacter('e', $tokenizer);
        $attributeValueUnquotedState->processCharacter('l', $tokenizer);
        $attributeValueUnquotedState->processCharacter('l', $tokenizer);
        $attributeValueUnquotedState->processCharacter('o', $tokenizer);

        $this->assertEquals('hello', $tagToken->getCurrentAttribute()->getValue());
    }

    public function testNamedCharacterReferenceInAttributeValue()
    {
        $tagToken = new StartTagToken();
        $tagToken->addAttribute(new AttributeStruct());

        $attributeValueUnquotedState = new AttributeValueUnquotedState();

        $tokenizer = new TestTokenizer();
        $tokenizer->setToken($tagToken);
        $tokenizer->setState($attributeValueUnquotedState);

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
        $attributeValueUnquotedState = new AttributeValueUnquotedState();
        $tokenizer = new TestTokenizer();

        // Careful, the state changes during the character processing.
        // It is set on the tokenizer.
        $attributeValueUnquotedState->processCharacter(' ', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\BeforeAttributeNameState', $tokenizer->getState());
    }

    public function testTagEnd()
    {
        $attributeValueUnquotedState = new AttributeValueUnquotedState();
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new StartTagToken());

        $attributeValueUnquotedState->processCharacter('>', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DataState', $tokenizer->getState());
        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\StartTagToken', $tokens[0]);
    }
}
