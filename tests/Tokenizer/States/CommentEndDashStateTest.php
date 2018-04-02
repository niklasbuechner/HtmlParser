<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\CommentEndDashState;
use HtmlParser\Tokenizer\Tokens\CommentToken;
use PHPUnit\Framework\TestCase;

class CommentEndDashStateTest extends TestCase
{
    public function testEndOfCommentTag()
    {
        $tokenizer = new TestTokenizer();
        $commentEndDashState = new CommentEndDashState();

        $commentEndDashState->processCharacter('-', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\CommentEndState', $tokenizer->getState());
    }

    public function testStrayDashInComment()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setCurrentToken(new CommentToken());

        $commentEndDashState = new CommentEndDashState();
        $commentEndDashState->processCharacter(' ', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\CommentState', $tokenizer->getState());
        $this->assertEquals('- ', $tokenizer->getCurrentToken()->getData());
    }
}
