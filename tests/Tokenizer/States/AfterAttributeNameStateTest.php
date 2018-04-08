<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\AfterAttributeNameState;
use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\StartTagToken;
use PHPUnit\Framework\TestCase;

class AfterAttributeNameStateTest extends TestCase
{
    public function testSolidus()
    {
        $tokenizer = new TestTokenizer();
        $afterAttributeNameState = new AfterAttributeNameState();

        $afterAttributeNameState->processCharacter('/', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\SelfClosingStartTagState', $tokenizer->getState());
    }

    public function testEqualsSign()
    {
        $tokenizer = new TestTokenizer();
        $afterAttributeNameState = new AfterAttributeNameState();

        $afterAttributeNameState->processCharacter('=', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\BeforeAttributeValueState', $tokenizer->getState());
    }

    public function testGreaterThanSign()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new StartTagToken());

        $afterAttributeNameState = new AfterAttributeNameState();
        $afterAttributeNameState->processCharacter('>', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DataState', $tokenizer->getState());
        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\StartTagToken', $tokens[0]);
    }

    public function testEndOfFile()
    {
        $tokenizer = new TestTokenizer();
        $afterAttributeNameState = new AfterAttributeNameState();

        $afterAttributeNameState->processCharacter(Tokenizer::END_OF_FILE_CHARACTER, $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndOfFileToken', $tokens[0]);
    }

    public function testNextAttributeName()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new StartTagToken());

        $afterAttributeNameState = new AfterAttributeNameState();
        $afterAttributeNameState->processCharacter('a', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\AttributeNameState', $tokenizer->getState());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Structs\AttributeStruct', $tokenizer->getToken()->getCurrentAttribute());
        $this->assertEquals('a', $tokenizer->getToken()->getCurrentAttribute()->getName());
    }

    public function testWhiteSpace()
    {
        $tokenizer = new TestTokenizer();
        $afterAttributeNameState = new AfterAttributeNameState();

        $afterAttributeNameState->processCharacter(' ', $tokenizer);
        $afterAttributeNameState->processCharacter('    ', $tokenizer);

        $this->assertNull($tokenizer->getState());
    }
}
