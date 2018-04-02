<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\TagNameState;
use HtmlParser\Tokenizer\States\TagOpenState;
use HtmlParser\Tokenizer\Tokens\StartTagToken;
use PHPUnit\Framework\TestCase;

class TagOpenStateTest extends TestCase
{
    public function testAsciiCharacter()
    {
        $tokenizer = new TestTokenizer();
        $tagOpenState = new TagOpenState();

        $tagOpenState->processCharacter('a', $tokenizer);

        $currentToken = $tokenizer->getCurrentToken();
        $this->assertInstanceOf('HtmlParser\Tokenizer\States\TagNameState', $tokenizer->getState());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\StartTagToken', $currentToken);
        $this->assertEquals('a', $currentToken->getName());
    }

    public function testClosingTagOpened()
    {
        $tokenizer = new TestTokenizer();
        $tagOpenState = new TagOpenState();

        $tagOpenState->processCharacter('/', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\EndTagOpenState', $tokenizer->getState());
    }

    public function testMarkupDeclarationOpenState()
    {
        $tokenizer = new TestTokenizer();
        $tagOpenState = new TagOpenState();

        $tagOpenState->processCharacter('!', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\MarkupDeclarationOpenState', $tokenizer->getState());
    }
}
