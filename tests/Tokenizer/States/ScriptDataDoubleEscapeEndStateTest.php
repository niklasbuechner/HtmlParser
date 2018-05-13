<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\ScriptDataDoubleEscapeEndState;
use HtmlParser\Tokenizer\Tokenizer;
use PHPUnit\Framework\TestCase;

class ScriptDataDoubleEscapeEndStateTest extends TestCase
{
    public function testWhiteSpace()
    {
        $this->runSpecialCharacterTest(' ', '', 'HtmlParser\Tokenizer\States\ScriptDataDoubleEscapedState');
    }

    public function testSolidus()
    {
        $this->runSpecialCharacterTest('/', '', 'HtmlParser\Tokenizer\States\ScriptDataDoubleEscapedState');
    }

    public function testGreaterThanSign()
    {
        $this->runSpecialCharacterTest('>', '', 'HtmlParser\Tokenizer\States\ScriptDataDoubleEscapedState');
    }

    public function testWhiteSpaceWithScript()
    {
        $this->runSpecialCharacterTest(' ', 'script', 'HtmlParser\Tokenizer\States\ScriptDataEscapedState');
    }

    public function testSolidusWithScript()
    {
        $this->runSpecialCharacterTest('/', 'script', 'HtmlParser\Tokenizer\States\ScriptDataEscapedState');
    }

    public function testGreaterThanSignWithScript()
    {
        $this->runSpecialCharacterTest('>', 'script', 'HtmlParser\Tokenizer\States\ScriptDataEscapedState');
    }

    public function testAsciiCharacter()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataDoubleEscapeEndState = new ScriptDataDoubleEscapeEndState();

        $scriptDataDoubleEscapeEndState->processCharacter('a', $tokenizer);
        $scriptDataDoubleEscapeEndState->processCharacter('A', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertEquals('aa', $tokenizer->getTemporaryBuffer());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('a', $tokens[0]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[1]);
        $this->assertEquals('A', $tokens[1]->getCharacter());
    }

    public function testOtherCharacter()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataDoubleEscapeEndState = new ScriptDataDoubleEscapeEndState();

        $scriptDataDoubleEscapeEndState->processCharacter('0', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\ScriptDataDoubleEscapedState', $tokenizer->getState());
    }

    private function runSpecialCharacterTest($character, $temporaryBuffer, $expectedState)
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->appendToTemporaryBuffer($temporaryBuffer);

        $scriptDataDoubleEscapeEndState = new ScriptDataDoubleEscapeEndState();
        $scriptDataDoubleEscapeEndState->processCharacter($character, $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf($expectedState, $tokenizer->getState());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals($character, $tokens[0]->getCharacter());
    }
}
