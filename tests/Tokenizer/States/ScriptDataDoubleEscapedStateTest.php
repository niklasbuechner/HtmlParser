<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\ScriptDataDoubleEscapedState;
use HtmlParser\Tokenizer\Tokenizer;
use PHPUnit\Framework\TestCase;

class ScriptDataDoubleEscapedStateTest extends TestCase
{
    public function testMinus()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataDoubleEscapedState = new ScriptDataDoubleEscapedState();

        $scriptDataDoubleEscapedState->processCharacter('-', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\ScriptDataDoubleEscapedDashState', $tokenizer->getState());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('-', $tokens[0]->getCharacter());
    }

    public function testLessThanSign()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataDoubleEscapedState = new ScriptDataDoubleEscapedState();

        $scriptDataDoubleEscapedState->processCharacter('<', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\ScriptDataDoubleEscapedLessThanSignState', $tokenizer->getState());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('<', $tokens[0]->getCharacter());
    }

    public function testEndOfFile()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataDoubleEscapedState = new ScriptDataDoubleEscapedState();

        $scriptDataDoubleEscapedState->processCharacter(Tokenizer::END_OF_FILE_CHARACTER, $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndOfFileToken', $tokens[0]);
    }

    public function testOtherCharacter()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataDoubleEscapedState = new ScriptDataDoubleEscapedState();

        $scriptDataDoubleEscapedState->processCharacter('0', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('0', $tokens[0]->getCharacter());
    }
}
