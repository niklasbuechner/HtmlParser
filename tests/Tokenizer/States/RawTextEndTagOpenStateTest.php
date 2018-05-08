<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\RawTextEndTagOpenState;
use HtmlParser\Tokenizer\Tokenizer;
use PHPUnit\Framework\TestCase;

class RawTextEndTagOpenStateTest extends TestCase
{
    public function testAsciiCharacter()
    {
        $tokenizer = new TestTokenizer();
        $rawTextEndTagOpenState = new RawTextEndTagOpenState();

        $rawTextEndTagOpenState->processCharacter('a', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\RawTextEndTagNameState', $tokenizer->getState());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndTagToken', $tokenizer->getToken());
    }

    public function testOtherCharacter()
    {
        $tokenizer = new TestTokenizer();
        $rawTextEndTagOpenState = new RawTextEndTagOpenState();

        $rawTextEndTagOpenState->processCharacter('-', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('<', $tokens[0]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[1]);
        $this->assertEquals('/', $tokens[1]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\States\RawTextState', $tokenizer->getState());
    }
}
