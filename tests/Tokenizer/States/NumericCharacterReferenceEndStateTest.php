<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\DataState;
use HtmlParser\Tokenizer\States\NumericCharacterReferenceEndState;
use HtmlParser\Tokenizer\Tokenizer;
use PHPUnit\Framework\TestCase;

class NumericCharacterReferenceEndStateTest extends TestCase
{
    public function testNullCharacter()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setCharacterReferenceCode(0x00c4);
        $tokenizer->setReturnState(new DataState());

        $numericCharacterReferenceEndState = new NumericCharacterReferenceEndState($tokenizer);
        $numericCharacterReferenceEndState->processCharacter('a', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(2, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('Ä', $tokens[0]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[1]);
        $this->assertEquals('a', $tokens[1]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DataState', $tokenizer->getState());
    }
}
