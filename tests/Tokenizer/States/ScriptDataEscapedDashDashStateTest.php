<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\ScriptDataEscapedDashDashState;
use HtmlParser\Tokenizer\Tokenizer;
use PHPUnit\Framework\TestCase;

class ScriptDataEscapedDashDashStateTest extends TestCase
{
    public function testMinus()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataEscapedDashDashState = new ScriptDataEscapedDashDashState();

        $scriptDataEscapedDashDashState->processCharacter('-', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('-', $tokens[0]->getCharacter());
    }

    public function testLessThanSign()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataEscapedDashDashState = new ScriptDataEscapedDashDashState();

        $scriptDataEscapedDashDashState->processCharacter('<', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\ScriptDataEscapedLessThanSignState', $tokenizer->getState());
    }

    public function testGreaterThanSign()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataEscapedDashDashState = new ScriptDataEscapedDashDashState();

        $scriptDataEscapedDashDashState->processCharacter('>', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('>', $tokens[0]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\States\ScriptDataState', $tokenizer->getState());
    }

    public function testEndOfFile()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataEscapedDashDashState = new ScriptDataEscapedDashDashState();

        $scriptDataEscapedDashDashState->processCharacter(Tokenizer::END_OF_FILE_CHARACTER, $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndOfFileToken', $tokens[0]);
    }

    public function testOtherCharacter()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataEscapedDashDashState = new ScriptDataEscapedDashDashState();

        $scriptDataEscapedDashDashState->processCharacter('a', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('a', $tokens[0]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\States\ScriptDataEscapedState', $tokenizer->getState());
    }
}
