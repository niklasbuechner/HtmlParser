<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\CommentLessThanSignBangState;
use HtmlParser\Tokenizer\Tokens\CommentToken;
use PHPUnit\Framework\TestCase;

class CommentLessThanSignBangStateTest extends TestCase
{
    public function testOpeningOfNestedComment()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setCurrentToken(new CommentToken());

        $commentLessThanSignBangState = new CommentLessThanSignBangState();
        $commentLessThanSignBangState->processCharacter('-', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\CommentLessThanSignBangDashState', $tokenizer->getState());
    }

    public function testStrayLessThanSignBang()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setCurrentToken(new CommentToken());

        $commentLessThanSignBangState = new CommentLessThanSignBangState();
        $commentLessThanSignBangState->processCharacter(' ', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\CommentState', $tokenizer->getState());
    }
}
