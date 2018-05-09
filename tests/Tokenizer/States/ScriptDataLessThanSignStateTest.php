<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\ScriptDataLessThanSignState;
use HtmlParser\Tokenizer\Tokenizer;
use PHPUnit\Framework\TestCase;

class ScriptDataLessThanSignStateTest extends TestCase
{
    public function testSolidus()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataLessThanSignState = new ScriptDataLessThanSignState();

        $scriptDataLessThanSignState->processCharacter('/', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\ScriptDataEndTagOpenState', $tokenizer->getState());
    }

    public function testExclamationMark()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataLessThanSignState = new ScriptDataLessThanSignState();

        $scriptDataLessThanSignState->processCharacter('!', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\ScriptDataEscapeStartState', $tokenizer->getState());
        $this->assertCount(2, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('<', $tokens[0]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[1]);
        $this->assertEquals('!', $tokens[1]->getCharacter());
    }

    public function testOtherCharacter()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataLessThanSignState = new ScriptDataLessThanSignState();

        $scriptDataLessThanSignState->processCharacter('a', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\ScriptDataState', $tokenizer->getState());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('<', $tokens[0]->getCharacter());
    }
}
