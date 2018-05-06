<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\RcdataLessThanSignState;
use HtmlParser\Tokenizer\Tokenizer;
use PHPUnit\Framework\TestCase;

class RcdataLessThanSignStateTest extends TestCase
{
    public function testSolidus()
    {
        $tokenizer = new TestTokenizer();
        $rcdataLessThanSignState = new RcdataLessThanSignState();

        $rcdataLessThanSignState->processCharacter('/', $tokenizer);

        $this->assertEmpty($tokenizer->getTemporaryBuffer());
        $this->assertInstanceOf('HtmlParser\Tokenizer\States\RcdataEndTagOpenState', $tokenizer->getState());
    }

    public function testOtherCharacter()
    {
        $tokenizer = new TestTokenizer();
        $rcdataLessThanSignState = new RcdataLessThanSignState();

        $rcdataLessThanSignState->processCharacter('a', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\RcdataState', $tokenizer->getState());
        $this->assertCount(2, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('<', $tokens[0]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[1]);
        $this->assertEquals('a', $tokens[1]->getCharacter());
    }
}
