<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\DataState;
use HtmlParser\Tokenizer\States\DecimalCharacterReferenceStartState;
use HtmlParser\Tokenizer\Tokenizer;
use PHPUnit\Framework\TestCase;

class DecimalCharacterReferenceStartStateTest extends TestCase
{
    public function testDigit()
    {
        $tokenizer = new TestTokenizer();
        $decimalCharacterReferenceStartState = new DecimalCharacterReferenceStartState();

        $decimalCharacterReferenceStartState->processCharacter('7', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DecimalCharacterReferenceState', $tokenizer->getState());
    }

    public function testOtherCharacter()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->appendToTemporaryBuffer('uio');
        $tokenizer->setReturnState(new DataState());

        $decimalCharacterReferenceStartState = new DecimalCharacterReferenceStartState();
        $decimalCharacterReferenceStartState->processCharacter('a', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DataState', $tokenizer->getState());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('u', $tokens[0]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[1]);
        $this->assertEquals('i', $tokens[1]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[2]);
        $this->assertEquals('o', $tokens[2]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[3]);
        $this->assertEquals('a', $tokens[3]->getCharacter());
    }
}
