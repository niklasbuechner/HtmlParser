<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\CommentEndBangState;
use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\CommentToken;
use PHPUnit\Framework\TestCase;

class CommentEndBangStateTest extends TestCase
{
    public function testDashInsteadOfCommentEnd()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new CommentToken());

        $commentEndBangState = new CommentEndBangState();
        $commentEndBangState->processCharacter('-', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\CommentEndDashState', $tokenizer->getState());
        $this->assertEquals('--!', $tokenizer->getToken()->getData());
    }

    public function testCommentEnd()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new CommentToken());

        $commentEndBangState = new CommentEndBangState();
        $commentEndBangState->processCharacter('>', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DataState', $tokenizer->getState());
        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CommentToken', $tokens[0]);
    }

    public function testEndOfFile()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new CommentToken());

        $commentEndBangState = new CommentEndBangState();
        $commentEndBangState->processCharacter(Tokenizer::END_OF_FILE_CHARACTER, $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(2, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CommentToken', $tokens[0]);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndOfFileToken', $tokens[1]);
    }

    public function testCommentContinuesWithData()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new CommentToken());

        $commentEndBangState = new CommentEndBangState();
        $commentEndBangState->processCharacter(' ', $tokenizer);

        $this->assertEquals('--! ', $tokenizer->getToken()->getData());
        $this->assertInstanceOf('HtmlParser\Tokenizer\States\CommentState', $tokenizer->getState());
    }
}
