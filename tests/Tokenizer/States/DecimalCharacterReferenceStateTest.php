<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\DataState;
use HtmlParser\Tokenizer\States\DecimalCharacterReferenceState;
use HtmlParser\Tokenizer\Tokenizer;
use PHPUnit\Framework\TestCase;

class DecimalCharacterReferenceStateTest extends TestCase
{
    public function testSemicolon()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setCharacterReferenceCode(0x00e4);
        $tokenizer->setReturnState(new DataState());

        $decimalCharacterReferenceState = new DecimalCharacterReferenceState();
        $decimalCharacterReferenceState->processCharacter(';', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('ä', $tokens[0]->getCharacter());
    }

    public function testDigit()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setCharacterReferenceCode(5);

        $decimalCharacterReferenceState = new DecimalCharacterReferenceState();
        $decimalCharacterReferenceState->processCharacter('6', $tokenizer);

        $this->assertEquals(56, $tokenizer->getCharacterReferenceCode());
    }

    public function testOtherCharacter()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setReturnState(new DataState());

        $decimalCharacterReferenceState = new DecimalCharacterReferenceState();
        $decimalCharacterReferenceState->processCharacter('a', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('a', $tokens[0]->getCharacter());
    }
}
