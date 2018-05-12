<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\ScriptDataEscapeStartDashState;
use HtmlParser\Tokenizer\Tokenizer;
use PHPUnit\Framework\TestCase;

class ScriptDataEscapeStartDashStateTest extends TestCase
{
    public function testMinus()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataEscapeStartDashState = new ScriptDataEscapeStartDashState();

        $scriptDataEscapeStartDashState->processCharacter('-', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\ScriptDataEscapedDashDashState', $tokenizer->getState());
        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('-', $tokens[0]->getCharacter());
    }

    public function testOtherCharacter()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataEscapeStartDashState = new ScriptDataEscapeStartDashState();

        $scriptDataEscapeStartDashState->processCharacter('a', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\ScriptDataState', $tokenizer->getState());
    }
}
