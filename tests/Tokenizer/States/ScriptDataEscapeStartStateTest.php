<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\ScriptDataEscapeStartState;
use HtmlParser\Tokenizer\Tokenizer;
use PHPUnit\Framework\TestCase;

class ScriptDataEscapeStartStateTest extends TestCase
{
    public function testMinus()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataEscapeStartState = new ScriptDataEscapeStartState();

        $scriptDataEscapeStartState->processCharacter('-', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\ScriptDataEscapeStartDashState', $tokenizer->getState());
        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('-', $tokens[0]->getCharacter());
    }

    public function testOtherCharacter()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataEscapeStartState = new ScriptDataEscapeStartState();

        $scriptDataEscapeStartState->processCharacter('a', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\ScriptDataState', $tokenizer->getState());
    }
}
