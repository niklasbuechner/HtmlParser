<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\CdataSectionState;
use HtmlParser\Tokenizer\Tokenizer;
use PHPUnit\Framework\TestCase;

class CdataSectionStateTest extends TestCase
{
    public function testRightSquareBracket()
    {
        $tokenizer = new TestTokenizer();
        $cdataSectionState = new CdataSectionState();

        $cdataSectionState->processCharacter(']', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\CdataSectionBracketState', $tokenizer->getState());
    }

    public function testEndOfFile()
    {
        $tokenizer = new TestTokenizer();
        $cdataSectionState = new CdataSectionState();

        $cdataSectionState->processCharacter(Tokenizer::END_OF_FILE_CHARACTER, $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndOfFileToken', $tokens[0]);
    }

    public function testAnyOtherCharacter()
    {
        $tokenizer = new TestTokenizer();
        $cdataSectionState = new CdataSectionState();

        $cdataSectionState->processCharacter('a', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('a', $tokens[0]->getCharacter());
    }
}
