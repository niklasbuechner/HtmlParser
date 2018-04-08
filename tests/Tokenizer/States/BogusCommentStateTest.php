<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\BogusCommentState;
use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\CommentToken;
use PHPUnit\Framework\TestCase;

class BogusCommentStateTest extends TestCase
{
    public function testCommentData()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setCurrentToken(new CommentToken());

        $bogusCommentState = new BogusCommentState();
        $bogusCommentState->processCharacter('h', $tokenizer);
        $bogusCommentState->processCharacter('e', $tokenizer);
        $bogusCommentState->processCharacter('l', $tokenizer);
        $bogusCommentState->processCharacter('l', $tokenizer);
        $bogusCommentState->processCharacter('o', $tokenizer);

        $this->assertEquals('hello', $tokenizer->getCurrentToken()->getData());
    }

    public function testEndOfBogusComment()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setCurrentToken(new CommentToken());

        $bogusCommentState = new BogusCommentState();
        $bogusCommentState->processCharacter('>', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CommentToken', $tokens[0]);
        $this->assertNull($tokens[0]->getData());
    }

    public function testEndOfFile()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setCurrentToken(new CommentToken());

        $bogusCommentState = new BogusCommentState();
        $bogusCommentState->processCharacter(Tokenizer::END_OF_FILE_CHARACTER, $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(2, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CommentToken', $tokens[0]);
        $this->assertNull($tokens[0]->getData());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndOfFileToken', $tokens[1]);
    }
}
