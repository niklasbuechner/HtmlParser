<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\ScriptDataDoubleEscapedLessThanSignState;
use HtmlParser\Tokenizer\Tokenizer;
use PHPUnit\Framework\TestCase;

class ScriptDataDoubleEscapedLessThanSignStateTest extends TestCase
{
    public function testSolidus()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->appendToTemporaryBuffer('aa');

        $scriptDataDoubleEscapedLessThanSignState = new ScriptDataDoubleEscapedLessThanSignState();
        $scriptDataDoubleEscapedLessThanSignState->processCharacter('/', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertEquals('', $tokenizer->getTemporaryBuffer());
        $this->assertInstanceOf('HtmlParser\Tokenizer\States\ScriptDataDoubleEscapeEndState', $tokenizer->getState());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('/', $tokens[0]->getCharacter());
    }

    public function testOtherCharacter()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataDoubleEscapedLessThanSignState = new ScriptDataDoubleEscapedLessThanSignState();

        $scriptDataDoubleEscapedLessThanSignState->processCharacter('0', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\ScriptDataDoubleEscapedState', $tokenizer->getState());
    }
}
