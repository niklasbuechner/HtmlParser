<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\CdataSectionBracketState;
use HtmlParser\Tokenizer\Tokenizer;
use PHPUnit\Framework\TestCase;

class CdataSectionBracketStateTest extends TestCase
{
    public function testRightBracket()
    {
        $tokenizer = new TestTokenizer();
        $cdataSectionBracketState = new CdataSectionBracketState();

        $cdataSectionBracketState->processCharacter(']', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\CdataSectionEndState', $tokenizer->getState());
    }

    public function testAnyOtherCharacter()
    {
        $tokenizer = new TestTokenizer();
        $cdataSectionBracketState = new CdataSectionBracketState();

        $cdataSectionBracketState->processCharacter('a', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(2, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals(']', $tokens[0]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[1]);
        $this->assertEquals('a', $tokens[1]->getCharacter());
    }
}
