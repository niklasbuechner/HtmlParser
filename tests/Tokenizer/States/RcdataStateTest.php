<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\RcdataState;
use HtmlParser\Tokenizer\Tokenizer;
use PHPUnit\Framework\TestCase;

class RcdataStateTest extends TestCase
{
    public function testCharacterReference()
    {
        $tokenizer = new TestTokenizer();
        $rcdataState = new RcdataState();

        $rcdataState->processCharacter('&', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\CharacterReferenceState', $tokenizer->getState());
        $this->assertInstanceOf('HtmlParser\Tokenizer\States\RcdataState', $tokenizer->getReturnState());
    }

    public function testOpenEndTag()
    {
        $tokenizer = new TestTokenizer();
        $rcdataState = new RcdataState();

        $rcdataState->processCharacter('<', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\RcdataLessThanSignState', $tokenizer->getState());
    }

    public function testEndOfFile()
    {
        $tokenizer = new TestTokenizer();
        $rcdataState = new RcdataState();

        $rcdataState->processCharacter(Tokenizer::END_OF_FILE_CHARACTER, $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndOfFileToken', $tokens[0]);
    }

    public function testAnyOtherCharacter()
    {
        $tokenizer = new TestTokenizer();
        $rcdataState = new RcdataState();

        $rcdataState->processCharacter('a', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('a', $tokens[0]->getCharacter());
    }
}
