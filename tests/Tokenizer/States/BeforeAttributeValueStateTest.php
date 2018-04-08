<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\BeforeAttributeValueState;
use HtmlParser\Tokenizer\Structs\AttributeStruct;
use HtmlParser\Tokenizer\Tokens\StartTagToken;
use PHPUnit\Framework\TestCase;

class BeforeAttributeValueStateTest extends TestCase
{
    public function testDoubleQuotedValueStart()
    {
        $tokenizer = new TestTokenizer();
        $beforeAttributeValueState = new BeforeAttributeValueState();

        $beforeAttributeValueState->processCharacter('"', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\AttributeValueDoubleQuotedState', $tokenizer->getState());
    }

    public function testSingleQuotedValueStart()
    {
        $tokenizer = new TestTokenizer();
        $beforeAttributeValueState = new BeforeAttributeValueState();

        $beforeAttributeValueState->processCharacter('\'', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\AttributeValueSingleQuotedState', $tokenizer->getState());
    }

    public function testUnquotedValueStart()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new StartTagToken());
        $tokenizer->getToken()->addAttribute(new AttributeStruct());

        $beforeAttributeValueState = new BeforeAttributeValueState();
        $beforeAttributeValueState->processCharacter('a', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\AttributeValueUnquotedState', $tokenizer->getState());
    }
}
