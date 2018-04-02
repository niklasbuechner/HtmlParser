<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\CommentStartState;
use HtmlParser\Tokenizer\Tokens\CommentToken;
use PHPUnit\Framework\TestCase;

class CommentStartStateTest extends TestCase
{
    public function testCommentDataString()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setCurrentToken(new CommentToken());

        $commentStartState = new CommentStartState();
        $commentStartState->processCharacter('h', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\CommentState', $tokenizer->getState());
    }

    public function testCommentDataStartingWithDash()
    {
        $tokenizer = new TestTokenizer();
        $commentStartState = new CommentStartState();

        $commentStartState->processCharacter('-', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\CommentStartDashState', $tokenizer->getState());
    }

    public function testIncorrectEmptyComment()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setCurrentToken(new CommentToken());

        $commentStartState = new CommentStartState();
        $commentStartState->processCharacter('>', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DataState', $tokenizer->getState());
        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CommentToken', $tokens[0]);
    }
}
