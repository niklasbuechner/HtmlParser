<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\RcdataEndTagNameState;
use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\EndTagToken;
use PHPUnit\Framework\TestCase;

class RcdataEndTagNameStateTest extends TestCase
{
    public function testWhiteSpace()
    {
        $tokenizer = new TestTokenizer();
        $rcdataEndTagNameState = new RcdataEndTagNameState();

        $rcdataEndTagNameState->processCharacter(' ', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\BeforeAttributeNameState', $tokenizer->getState());
    }

    public function testSolidus()
    {
        $tokenizer = new TestTokenizer();
        $rcdataEndTagNameState = new RcdataEndTagNameState();

        $rcdataEndTagNameState->processCharacter('/', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\SelfClosingStartTagState', $tokenizer->getState());
    }

    public function testEndOfRcdataTag()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new EndTagToken());

        $rcdataEndTagNameState = new RcdataEndTagNameState();
        $rcdataEndTagNameState->processCharacter('>', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndTagToken', $tokens[0]);
        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DataState', $tokenizer->getState());
    }

    public function testAsciiCharacter()
    {
        $tokenizer = new TestTokenizer();
        $rcdataEndTagNameState = new RcdataEndTagNameState();

        $rcdataEndTagNameState->processCharacter('a', $tokenizer);

        $this->assertEquals('a', $tokenizer->getTemporaryBuffer());
    }

    public function testOtherCharacter()
    {
        $tokenizer = new TestTokenizer();
        $rcdataEndTagNameState = new RcdataEndTagNameState();

        $rcdataEndTagNameState->processCharacter('a', $tokenizer);
        $rcdataEndTagNameState->processCharacter('b', $tokenizer);
        $rcdataEndTagNameState->processCharacter('-', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('<', $tokens[0]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[1]);
        $this->assertEquals('/', $tokens[1]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[2]);
        $this->assertEquals('a', $tokens[2]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[3]);
        $this->assertEquals('b', $tokens[3]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\States\RcdataState', $tokenizer->getState());
    }
}
