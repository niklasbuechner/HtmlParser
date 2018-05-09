<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\ScriptDataEndTagNameState;
use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\EndTagToken;
use PHPUnit\Framework\TestCase;

class ScriptDataEndTagNameStateTest extends TestCase
{
    public function testSolidus()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataEndTagNameState = new ScriptDataEndTagNameState();

        $scriptDataEndTagNameState->processCharacter('/', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\SelfClosingStartTagState', $tokenizer->getState());
    }

    public function testGreaterThanSign()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new EndTagToken());

        $scriptDataEndTagNameState = new ScriptDataEndTagNameState();
        $scriptDataEndTagNameState->processCharacter('>', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DataState', $tokenizer->getState());
        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndTagToken', $tokens[0]);
    }

    public function testAsciiCharacter()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new EndTagToken());

        $scriptDataEndTagNameState = new ScriptDataEndTagNameState();
        $scriptDataEndTagNameState->processCharacter('A', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertEquals('a', $tokenizer->getToken()->getName());
        $this->assertEquals('A', $tokenizer->getTemporaryBuffer());
    }

    public function testWhiteSpace()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataEndTagNameState = new ScriptDataEndTagNameState();

        $scriptDataEndTagNameState->processCharacter(' ', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\BeforeAttributeNameState', $tokenizer->getState());
    }

    public function testOtherCharacter()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new EndTagToken());

        $scriptDataEndTagNameState = new ScriptDataEndTagNameState();
        $scriptDataEndTagNameState->processCharacter('a', $tokenizer);
        $scriptDataEndTagNameState->processCharacter('b', $tokenizer);
        $scriptDataEndTagNameState->processCharacter('-', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\ScriptDataState', $tokenizer->getState());
        $this->assertCount(5, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('<', $tokens[0]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[1]);
        $this->assertEquals('/', $tokens[1]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[2]);
        $this->assertEquals('a', $tokens[2]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[3]);
        $this->assertEquals('b', $tokens[3]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[4]);
        $this->assertEquals('-', $tokens[4]->getCharacter());
    }
}
