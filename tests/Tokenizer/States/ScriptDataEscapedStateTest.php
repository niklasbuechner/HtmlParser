<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\ScriptDataEscapedState;
use HtmlParser\Tokenizer\Tokenizer;
use PHPUnit\Framework\TestCase;

class ScriptDataEscapedStateTest extends TestCase
{
    public function testMinus()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataEscapedState = new ScriptDataEscapedState();

        $scriptDataEscapedState->processCharacter('-', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\ScriptDataEscapedDashState', $tokenizer->getState());
        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('-', $tokens[0]->getCharacter());
    }

    public function testLessThanSign()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataEscapedState = new ScriptDataEscapedState();

        $scriptDataEscapedState->processCharacter('<', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\ScriptDataEscapedLessThanSignState', $tokenizer->getState());
    }

    public function testEndOfFile()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataEscapedState = new ScriptDataEscapedState();

        $scriptDataEscapedState->processCharacter(Tokenizer::END_OF_FILE_CHARACTER, $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndOfFileToken', $tokens[0]);
    }

    public function testOtherCharacter()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataEscapedState = new ScriptDataEscapedState();

        $scriptDataEscapedState->processCharacter('a', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('a', $tokens[0]->getCharacter());
    }
}
