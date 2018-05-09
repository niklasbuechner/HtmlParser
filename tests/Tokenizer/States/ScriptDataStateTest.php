<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\ScriptDataState;
use HtmlParser\Tokenizer\Tokenizer;
use PHPUnit\Framework\TestCase;

class ScriptDataStateTest extends TestCase
{
    public function testLessThanSign()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataState = new ScriptDataState();

        $scriptDataState->processCharacter('<', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\ScriptDataLessThanSignState', $tokenizer->getState());
    }

    public function testEndOfFile()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataState = new ScriptDataState();

        $scriptDataState->processCharacter(Tokenizer::END_OF_FILE_CHARACTER, $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndOfFileToken', $tokens[0]);
    }

    public function testOtherCharacter()
    {
        $tokenizer = new TestTokenizer();
        $scriptDataState = new ScriptDataState();

        $scriptDataState->processCharacter('a', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('a', $tokens[0]->getCharacter());
    }
}
