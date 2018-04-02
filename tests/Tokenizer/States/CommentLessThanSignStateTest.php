<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\CommentLessThanSignState;
use HtmlParser\Tokenizer\Tokens\CommentToken;
use PHPUnit\Framework\TestCase;

class CommentLessThanSignStateTest extends TestCase
{
    public function testSecondLessThanSign()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setCurrentToken(new CommentToken());

        $commentLessThanSignState = new CommentLessThanSignState();
        $commentLessThanSignState->processCharacter('<', $tokenizer);

        $this->assertNull($tokenizer->getState());
        $this->assertEquals('<', $tokenizer->getCurrentToken()->getData());
    }

    public function testStrayLessThanSign()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setCurrentToken(new CommentToken());

        $commentLessThanSignState = new CommentLessThanSignState();
        $commentLessThanSignState->processCharacter(' ', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\CommentState', $tokenizer->getState());
        $this->assertEquals(' ', $tokenizer->getCurrentToken()->getData());
    }

    public function testExclamationMarkOfNestedComment()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setCurrentToken(new CommentToken());

        $commentLessThanSignState = new CommentLessThanSignState();
        $commentLessThanSignState->processCharacter('!', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\CommentLessThanSignBangState', $tokenizer->getState());
        $this->assertEquals('!', $tokenizer->getCurrentToken()->getData());
    }
}
