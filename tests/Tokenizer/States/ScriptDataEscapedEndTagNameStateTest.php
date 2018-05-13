<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\ScriptDataEscapedEndTagNameState;
use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\EndTagToken;
use PHPUnit\Framework\TestCase;

class ScriptDataEscapedEndTagNameStateTest extends TestCase
{
    public function testSolidus()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataEscapedEndTagNameState = new ScriptDataEscapedEndTagNameState();

        $scriptDataEscapedEndTagNameState->processCharacter('/', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\SelfClosingStartTagState', $tokenizer->getState());
    }

    public function testWhiteSpace()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataEscapedEndTagNameState = new ScriptDataEscapedEndTagNameState();

        $scriptDataEscapedEndTagNameState->processCharacter(' ', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\BeforeAttributeNameState', $tokenizer->getState());
    }

    public function testGreaterThanSign()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new EndTagToken());

        $scriptDataEscapedEndTagNameState = new ScriptDataEscapedEndTagNameState();
        $scriptDataEscapedEndTagNameState->processCharacter('>', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DataState', $tokenizer->getState());
        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndTagToken', $tokens[0]);
    }

    public function testAsciiCharacter()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new EndTagToken());

        $scriptDataEscapedEndTagNameState = new ScriptDataEscapedEndTagNameState();
        $scriptDataEscapedEndTagNameState->processCharacter('a', $tokenizer);
        $scriptDataEscapedEndTagNameState->processCharacter('A', $tokenizer);

        $this->assertEquals('aA', $tokenizer->getTemporaryBuffer());
        $this->assertEquals('aa', $tokenizer->getToken()->getName());
    }

    public function testOtherCharacter()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->appendToTemporaryBuffer('ab');

        $scriptDataEscapedEndTagNameState = new ScriptDataEscapedEndTagNameState();
        $scriptDataEscapedEndTagNameState->processCharacter('0', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('<', $tokens[0]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[1]);
        $this->assertEquals('/', $tokens[1]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[2]);
        $this->assertEquals('a', $tokens[2]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[3]);
        $this->assertEquals('b', $tokens[3]->getCharacter());
    }
}
