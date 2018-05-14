<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\DataState;
use HtmlParser\Tokenizer\States\HexadecimalCharacterReferenceStartState;
use HtmlParser\Tokenizer\Tokenizer;
use PHPUnit\Framework\TestCase;

class HexadecimalCharacterReferenceStartStateTest extends TestCase
{
    public function testHexadecimalCharacter()
    {
        $tokenizer = new TestTokenizer();
        $hexadecimalCharacterReferenceStartState = new HexadecimalCharacterReferenceStartState();

        $hexadecimalCharacterReferenceStartState->processCharacter('a', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\HexadecimalCharacterReferenceState', $tokenizer->getState());
    }

    public function testOtherCharacter()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->appendToTemporaryBuffer('uio');
        $tokenizer->setReturnState(new DataState());

        $hexadecimalCharacterReferenceStartState = new HexadecimalCharacterReferenceStartState();
        $hexadecimalCharacterReferenceStartState->processCharacter('x', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DataState', $tokenizer->getState());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('u', $tokens[0]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[1]);
        $this->assertEquals('i', $tokens[1]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[2]);
        $this->assertEquals('o', $tokens[2]->getCharacter());
    }
}
