<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\ScriptDataEscapedLessThanSignState;
use HtmlParser\Tokenizer\Tokenizer;
use PHPUnit\Framework\TestCase;

class ScriptDataEscapedLessThanSignStateTest extends TestCase
{
    public function testSolidus()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataEscapedLessThanSignState = new ScriptDataEscapedLessThanSignState();

        $scriptDataEscapedLessThanSignState->processCharacter('/', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\ScriptDataEscapedEndTagOpenState', $tokenizer->getState());
    }

    public function testAsciiCharacter()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataEscapedLessThanSignState = new ScriptDataEscapedLessThanSignState();

        $scriptDataEscapedLessThanSignState->processCharacter('a', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('<', $tokens[0]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\States\ScriptDataDoubleEscapeStartState', $tokenizer->getState());
    }

    public function testOtherCharacter()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataEscapedLessThanSignState = new ScriptDataEscapedLessThanSignState();

        $scriptDataEscapedLessThanSignState->processCharacter('0', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('<', $tokens[0]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\States\ScriptDataEscapedState', $tokenizer->getState());
    }
}
