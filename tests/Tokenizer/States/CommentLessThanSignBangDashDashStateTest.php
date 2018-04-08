<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\CommentLessThanSignBangDashDashState;
use HtmlParser\Tokenizer\Tokens\CommentToken;
use PHPUnit\Framework\TestCase;

class CommentLessThanSignBangDashDashStateTest extends TestCase
{
    public function testForEndOfNestedComment()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new CommentToken());

        $commentLessThanSignBangDashDashState = new CommentLessThanSignBangDashDashState();
        $commentLessThanSignBangDashDashState->processCharacter('>', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DataState', $tokenizer->getState());
        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CommentToken', $tokens[0]);
    }

    public function testForOpeningNestedCommentWithoutClosing()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new CommentToken());

        $commentLessThanSignBangDashDashState = new CommentLessThanSignBangDashDashState();
        $commentLessThanSignBangDashDashState->processCharacter(' ', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\CommentState', $tokenizer->getState());
    }
}
