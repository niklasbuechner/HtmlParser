<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\ScriptDataEndTagOpenState;
use HtmlParser\Tokenizer\Tokenizer;
use PHPUnit\Framework\TestCase;

class ScriptDataEndTagOpenStateTest extends TestCase
{
    public function testAsciiCharacter()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataEndTagOpenState = new ScriptDataEndTagOpenState();

        $scriptDataEndTagOpenState->processCharacter('a', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndTagToken', $tokenizer->getToken());
        $this->assertInstanceOf('HtmlParser\Tokenizer\States\ScriptDataEndTagNameState', $tokenizer->getState());
    }

    public function testNonAsciiCharacter()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataEndTagOpenState = new ScriptDataEndTagOpenState();

        $scriptDataEndTagOpenState->processCharacter('-', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('<', $tokens[0]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[1]);
        $this->assertEquals('/', $tokens[1]->getCharacter());
    }
}
