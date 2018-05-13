<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\ScriptDataEscapedEndTagOpenState;
use HtmlParser\Tokenizer\Tokenizer;
use PHPUnit\Framework\TestCase;

class ScriptDataEscapedEndTagOpenStateTest extends TestCase
{
    public function testAsciiCharacter()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataEscapedEndTagOpenState = new ScriptDataEscapedEndTagOpenState();

        $scriptDataEscapedEndTagOpenState->processCharacter('a', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndTagToken', $tokenizer->getToken());
        $this->assertInstanceOf('HtmlParser\Tokenizer\States\ScriptDataEscapedEndTagNameState', $tokenizer->getState());
    }

    public function testOtherCharacter()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataEscapedEndTagOpenState = new ScriptDataEscapedEndTagOpenState();

        $scriptDataEscapedEndTagOpenState->processCharacter('0', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('<', $tokens[0]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[1]);
        $this->assertEquals('/', $tokens[1]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\States\ScriptDataEscapedState', $tokenizer->getState());
    }
}
