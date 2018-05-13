<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\AmbiguousAmbersandState;
use HtmlParser\Tokenizer\States\DataState;
use HtmlParser\Tokenizer\Tokenizer;
use PHPUnit\Framework\TestCase;

class AmbiguousAmbersandStateTest extends TestCase
{
    public function testAlphaNumberic()
    {
        $tokenizer = new TestTokenizer();
        $ambiguousAmbersandState = new AmbiguousAmbersandState();

        $ambiguousAmbersandState->processCharacter('a', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('a', $tokens[0]->getCharacter());
    }

    public function testSemicolon()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setReturnState(new DataState());
        $ambiguousAmbersandState = new AmbiguousAmbersandState();

        $ambiguousAmbersandState->processCharacter(';', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DataState', $tokenizer->getState());
    }

    public function testOtherCharacter()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setReturnState(new DataState());
        $ambiguousAmbersandState = new AmbiguousAmbersandState();

        $ambiguousAmbersandState->processCharacter('-', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DataState', $tokenizer->getState());
    }
}
