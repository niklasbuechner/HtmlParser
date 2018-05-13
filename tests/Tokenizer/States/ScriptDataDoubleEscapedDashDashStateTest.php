<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\ScriptDataDoubleEscapedDashDashState;
use HtmlParser\Tokenizer\Tokenizer;
use PHPUnit\Framework\TestCase;

class ScriptDataDoubleEscapedDashDashStateTest extends TestCase
{
    public function testMinus()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataDoubleEscapedDashDashState = new ScriptDataDoubleEscapedDashDashState();

        $scriptDataDoubleEscapedDashDashState->processCharacter('-', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('-', $tokens[0]->getCharacter());
    }

    public function testLessThanSign()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataDoubleEscapedDashDashState = new ScriptDataDoubleEscapedDashDashState();

        $scriptDataDoubleEscapedDashDashState->processCharacter('<', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('<', $tokens[0]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\States\ScriptDataDoubleEscapedLessThanSignState', $tokenizer->getState());
    }

    public function testGreaterThanSign()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataDoubleEscapedDashDashState = new ScriptDataDoubleEscapedDashDashState();

        $scriptDataDoubleEscapedDashDashState->processCharacter('>', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('>', $tokens[0]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\States\ScriptDataState', $tokenizer->getState());
    }

    public function testEndOfFile()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataDoubleEscapedDashDashState = new ScriptDataDoubleEscapedDashDashState();

        $scriptDataDoubleEscapedDashDashState->processCharacter(Tokenizer::END_OF_FILE_CHARACTER, $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndOfFileToken', $tokens[0]);
    }

    public function testOtherCharacter()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataDoubleEscapedDashDashState = new ScriptDataDoubleEscapedDashDashState();

        $scriptDataDoubleEscapedDashDashState->processCharacter('0', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('0', $tokens[0]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\States\ScriptDataDoubleEscapedState', $tokenizer->getState());
    }
}
