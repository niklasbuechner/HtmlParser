<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\TagNameState;
use HtmlParser\Tokenizer\States\TagOpenState;
use HtmlParser\Tokenizer\Tokenizer;
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

    public function testEndOfFile()
    {
        $tokenizer = new TestTokenizer();
        $tagOpenState = new TagOpenState();

        $tagOpenState->processCharacter(Tokenizer::END_OF_FILE_CHARACTER, $tokenizer);
        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(2, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('<', $tokens[0]->getCharacter());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndOfFileToken', $tokens[1]);
    }

    public function testStrayLessThanSign()
    {
        $tokenizer = new TestTokenizer();
        $tagOpenState = new TagOpenState();

        $tagOpenState->processCharacter(' ', $tokenizer);
        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CharacterToken', $tokens[0]);
        $this->assertEquals('<', $tokens[0]->getCharacter());
    }

    public function testBogusComment()
    {
        $tokenizer = new TestTokenizer();
        $tagOpenState = new TagOpenState();

        $tagOpenState->processCharacter('?', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\BogusCommentState', $tokenizer->getState());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CommentToken', $tokenizer->getCurrentToken());
    }
}
