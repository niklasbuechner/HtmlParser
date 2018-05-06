<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\CdataSectionEndState;
use HtmlParser\Tokenizer\Tokenizer;
use PHPUnit\Framework\TestCase;

class CdataSectionEndStateTest extends TestCase
{
    public function testRightBracket()
    {
        $tokenizer = new TestTokenizer();
        $cdataSectionEndState = new CdataSectionEndState();

        $cdataSectionEndState->processCharacter(']', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals(']', $tokens[0]->getCharacter());
    }

    public function testEndOfCdataSection()
    {
        $tokenizer = new TestTokenizer();
        $cdataSectionEndState = new CdataSectionEndState();

        $cdataSectionEndState->processCharacter('>', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DataState', $tokenizer->getState());
    }

    public function testAnyOtherCharacter()
    {
        $tokenizer = new TestTokenizer();
        $cdataSectionEndState = new CdataSectionEndState();

        $cdataSectionEndState->processCharacter('a', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(3, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals(']', $tokens[0]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[1]);
        $this->assertEquals(']', $tokens[1]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[2]);
        $this->assertEquals('a', $tokens[2]->getCharacter());
    }
}
