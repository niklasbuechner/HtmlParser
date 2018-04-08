<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\BeforeAttributeNameState;
use HtmlParser\Tokenizer\States\DataState;
use HtmlParser\Tokenizer\States\TagNameState;
use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\StartTagToken;
use PHPUnit\Framework\TestCase;

class TagNameStateTest extends TestCase
{
    public function testTagNameEndingWithASpace()
    {
        $tokenizer = new TestTokenizer();
        $tagNameState = new TagNameState();

        $tokenizer->setToken(new StartTagToken());
        $tagNameState->processCharacter('d', $tokenizer);
        $tagNameState->processCharacter('i', $tokenizer);
        $tagNameState->processCharacter('v', $tokenizer);
        $tagNameState->processCharacter(' ', $tokenizer);

        $currentToken = $tokenizer->getToken();

        $this->assertEquals('div', $currentToken->getName());
        $this->assertInstanceOf('HtmlParser\Tokenizer\States\BeforeAttributeNameState', $tokenizer->getState());
    }

    public function testTagNameEndingInTheClosingTagBracket()
    {
        $tokenizer = new TestTokenizer();
        $tagNameState = new TagNameState();

        $tokenizer->setToken(new StartTagToken());
        $tagNameState->processCharacter('p', $tokenizer);
        $tagNameState->processCharacter('>', $tokenizer);

        $currentToken = $tokenizer->getToken();
        $tokenListener = $tokenizer->getTokenListener();
        $emittedTokens = $tokenListener->getEmittedTokens();

        $this->assertEquals('p', $currentToken->getName());
        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DataState', $tokenizer->getState());
        $this->assertEquals(1, count($emittedTokens));
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\StartTagToken', $emittedTokens[0]);
    }

    public function testEndOfLine()
    {
        $tokenizer = new TestTokenizer();
        $tagNameState = new TagNameState();

        $tagNameState->processCharacter(Tokenizer::END_OF_FILE_CHARACTER, $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndOfFileToken', $tokens[0]);
    }

    public function testSelfClosingTag()
    {
        $tokenizer = new TestTokenizer();
        $tagNameState = new TagNameState();

        $tokenizer->setToken(new StartTagToken());
        $tagNameState->processCharacter('/', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\SelfClosingStartTagState', $tokenizer->getState());
    }
}
