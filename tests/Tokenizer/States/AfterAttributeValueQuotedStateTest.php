<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\AfterAttributeValueQuotedState;
use HtmlParser\Tokenizer\Tokens\StartTagToken;
use PHPUnit\Framework\TestCase;

class AfterAttributeValueQuotedStateTest extends TestCase
{
    public function testChangeToBeforeNextAttribute()
    {
        $tokenizer = new TestTokenizer();
        $afterAttributeValueQuotedState = new AfterAttributeValueQuotedState();

        $afterAttributeValueQuotedState->processCharacter(' ', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\BeforeAttributeNameState', $tokenizer->getState());
    }

    public function testEndOfTag()
    {
        $tagToken = new StartTagToken();
        $tokenizer = new TestTokenizer();
        $tokenizer->setCurrentToken($tagToken);

        $afterAttributeValueQuotedState = new AfterAttributeValueQuotedState();
        $afterAttributeValueQuotedState->processCharacter('>', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DataState', $tokenizer->getState());

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\StartTagToken', $tokens[0]);
    }
}
