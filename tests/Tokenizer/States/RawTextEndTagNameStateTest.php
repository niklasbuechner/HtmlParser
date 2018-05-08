<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\RawTextEndTagNameState;
use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\EndTagToken;
use PHPUnit\Framework\TestCase;

class RawTextEndTagNameStateTest extends TestCase
{
    public function testClosingTag()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new EndTagToken());

        $rawTextEndTagNameState = new RawTextEndTagNameState();
        $rawTextEndTagNameState->processCharacter('>', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndTagToken', $tokenizer->getToken());
        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DataState', $tokenizer->getState());
    }

    public function testSolidus()
    {
        $tokenizer = new TestTokenizer();
        $rawTextEndTagNameState = new RawTextEndTagNameState();

        $rawTextEndTagNameState->processCharacter('/', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\SelfClosingStartTagState', $tokenizer->getState());
    }

    public function testAsciiCharacter()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new EndTagToken());

        $rawTextEndTagNameState = new RawTextEndTagNameState();
        $rawTextEndTagNameState->processCharacter('A', $tokenizer);

        $this->assertEquals('a', $tokenizer->getToken()->getName());
        $this->assertEquals('A', $tokenizer->getTemporaryBuffer());
    }

    public function testWhiteSpace()
    {
        $tokenizer = new TestTokenizer();
        $rawTextEndTagNameState = new RawTextEndTagNameState();

        $rawTextEndTagNameState->processCharacter(' ', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\BeforeAttributeNameState', $tokenizer->getState());
    }

    public function testOtherCharacter()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new EndTagToken());

        $rawTextEndTagNameState = new RawTextEndTagNameState();
        $rawTextEndTagNameState->processCharacter('a', $tokenizer);
        $rawTextEndTagNameState->processCharacter('b', $tokenizer);
        $rawTextEndTagNameState->processCharacter('-', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

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
