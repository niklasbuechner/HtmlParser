<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\ScriptDataDoubleEscapeStartState;
use HtmlParser\Tokenizer\Tokenizer;
use PHPUnit\Framework\TestCase;

class ScriptDataDoubleEscapeStartStateTest extends TestCase
{
    public function testWhiteSpaceWith()
    {
        $this->runSpecialCharacterTest(' ', '', 'HtmlParser\Tokenizer\States\ScriptDataEscapedState');
    }

    public function testSolidusWith()
    {
        $this->runSpecialCharacterTest('/', '', 'HtmlParser\Tokenizer\States\ScriptDataEscapedState');
    }

    public function testGreaterThanSignWith()
    {
        $this->runSpecialCharacterTest('>', '', 'HtmlParser\Tokenizer\States\ScriptDataEscapedState');
    }

    public function testWhiteSpaceWithForScriptTag()
    {
        $this->runSpecialCharacterTest(' ', 'script', 'HtmlParser\Tokenizer\States\ScriptDataDoubleEscapedState');
    }

    public function testSolidusForScriptTag()
    {
        $this->runSpecialCharacterTest('/', 'script', 'HtmlParser\Tokenizer\States\ScriptDataDoubleEscapedState');
    }

    public function testGreaterThanSignForScriptTag()
    {
        $this->runSpecialCharacterTest('>', 'script', 'HtmlParser\Tokenizer\States\ScriptDataDoubleEscapedState');
    }

    public function testAsciiCharacter()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataDoubleEscapeStartState = new ScriptDataDoubleEscapeStartState();

        $scriptDataDoubleEscapeStartState->processCharacter('a', $tokenizer);
        $scriptDataDoubleEscapeStartState->processCharacter('A', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertEquals($tokenizer->getTemporaryBuffer(), 'aa');
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('a', $tokens[0]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[1]);
        $this->assertEquals('A', $tokens[1]->getCharacter());
    }

    public function testOtherCharacter()
    {
        $tokenizer = new TestTokenizer();

        $scriptDataDoubleEscapeStartState = new ScriptDataDoubleEscapeStartState();
        $scriptDataDoubleEscapeStartState->processCharacter('0', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\ScriptDataEscapedState', $tokenizer->getState());
    }

    private function runSpecialCharacterTest($character, $temporaryBuffer, $finalState)
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->appendToTemporaryBuffer($temporaryBuffer);

        $scriptDataDoubleEscapeStartState = new ScriptDataDoubleEscapeStartState();
        $scriptDataDoubleEscapeStartState->processCharacter($character, $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf($finalState, $tokenizer->getState());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals($character, $tokens[0]->getCharacter());
    }
}
