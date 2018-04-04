<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\CommentLessThanSignBangDashState;
use HtmlParser\Tokenizer\Tokens\CommentToken;
use PHPUnit\Framework\TestCase;

class CommentLessThanSignBangDashStateTest extends TestCase
{
    public function testForLessThanSignBangDashDash()
    {
        $tokenizer = new TestTokenizer();

        $commentLessThanSignBangDashState = new CommentLessThanSignBangDashState();
        $commentLessThanSignBangDashState->processCharacter('-', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\CommentLessThanSignBangDashDashState', $tokenizer->getState());
    }

    public function testForStrayLessThanSignBangDashDash()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setCurrentToken(new CommentToken());

        $commentLessThanSignBangDashState = new CommentLessThanSignBangDashState();
        $commentLessThanSignBangDashState->processCharacter(' ', $tokenizer);

        // Please note that the state is changed by CommentEndDashState since the character
        // is reconsumed there and therefore does not retain the state set in CommentLessThanSignBangDashState.
        $this->assertInstanceOf('HtmlParser\Tokenizer\States\CommentState', $tokenizer->getState());
    }
}
