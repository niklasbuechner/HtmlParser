<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\BeforeAttributeNameState;
use HtmlParser\Tokenizer\States\DataState;
use HtmlParser\Tokenizer\States\TagNameState;
use HtmlParser\Tokenizer\Tokens\TagToken;
use PHPUnit\Framework\TestCase;

class TagNameStateTest extends TestCase
{
    public function testTagNameEndingWithASpace()
    {
        $tokenizer = new TestTokenizer();
        $tagNameState = new TagNameState();

        $tokenizer->setCurrentToken(new TagToken());
        $tagNameState->processCharacter('d', $tokenizer);
        $tagNameState->processCharacter('i', $tokenizer);
        $tagNameState->processCharacter('v', $tokenizer);
        $tagNameState->processCharacter(' ', $tokenizer);

        $currentToken = $tokenizer->getCurrentToken();

        $this->assertEquals('div', $currentToken->getName());
        $this->assertTrue($tokenizer->getState() instanceof BeforeAttributeNameState);
    }

    public function testTagNameEndingInTheClosingTagBracket()
    {
        $tokenizer = new TestTokenizer();
        $tagNameState = new TagNameState();

        $tokenizer->setCurrentToken(new TagToken());
        $tagNameState->processCharacter('p', $tokenizer);
        $tagNameState->processCharacter('>', $tokenizer);

        $currentToken = $tokenizer->getCurrentToken();
        $tokenListener = $tokenizer->getTokenListener();
        $emittedTokens = $tokenListener->getEmittedTokens();

        $this->assertEquals('p', $currentToken->getName());
        $this->assertTrue($tokenizer->getState() instanceof DataState);
        $this->assertEquals(1, count($emittedTokens));
        $this->assertTrue($emittedTokens[0] instanceof TagToken);
    }
}
