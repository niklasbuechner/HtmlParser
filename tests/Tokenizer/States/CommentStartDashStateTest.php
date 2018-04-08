<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\CommentStartDashState;
use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\CommentToken;
use PHPUnit\Framework\TestCase;

class CommentStartDashStateTest extends TestCase
{
    public function testCommentEndWithoutData()
    {
        $tokenizer = new TestTokenizer();
        $commentStartDashState = new CommentStartDashState();

        $commentStartDashState->processCharacter('-', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\CommentEndState', $tokenizer->getState());
    }

    public function testEndOfFile()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new CommentToken());

        $commentStartDashState = new CommentStartDashState();
        $commentStartDashState->processCharacter(Tokenizer::END_OF_FILE_CHARACTER, $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(2, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CommentToken', $tokens[0]);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndOfFileToken', $tokens[1]);
    }

    public function testAbruptClosing()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new CommentToken());

        $commentStartDashState = new CommentStartDashState();
        $commentStartDashState->processCharacter('>', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DataState', $tokenizer->getState());
        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CommentToken', $tokens[0]);
    }

    public function testDataAfterThridDash()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new CommentToken());

        $commentStartDashState = new CommentStartDashState();
        $commentStartDashState->processCharacter(' ', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\CommentState', $tokenizer->getState());
        $this->assertEquals('- ', $tokenizer->getToken()->getData());
    }
}
