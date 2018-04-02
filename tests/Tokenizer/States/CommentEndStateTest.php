<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\CommentEndState;
use HtmlParser\Tokenizer\Tokens\CommentToken;
use PHPUnit\Framework\TestCase;

class CommentEndStateTest extends TestCase
{
    public function testEndOfComment()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setCurrentToken(new CommentToken());

        $commentEndState = new CommentEndState();
        $commentEndState->processCharacter('>', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DataState', $tokenizer->getState());
        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CommentToken', $tokens[0]);
    }

    public function testThirdDash()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setCurrentToken(new CommentToken());

        $commentEndState = new CommentEndState();
        $commentEndState->processCharacter('-', $tokenizer);

        $this->assertEquals('-', $tokenizer->getCurrentToken()->getData());
    }

    public function testTwoStrayDashes()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setCurrentToken(new CommentToken());

        $commentEndState = new CommentEndState();
        $commentEndState->processCharacter(' ', $tokenizer);

        $this->assertEquals('-- ', $tokenizer->getCurrentToken()->getData());
    }

    public function testCommentEndingInBang()
    {
        $tokenizer = new TestTokenizer();
        $commentEndState = new CommentEndState();

        $commentEndState->processCharacter('!', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\CommentEndBangState', $tokenizer->getState());
    }
}
