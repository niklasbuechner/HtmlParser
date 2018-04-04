<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\CommentState;
use HtmlParser\Tokenizer\Tokens\CommentToken;
use PHPUnit\Framework\TestCase;

class CommentStateTest extends TestCase
{
    public function testAddCharacters()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setCurrentToken(new CommentToken());

        $commentState = new CommentState();

        $commentState->processCharacter('h', $tokenizer);
        $commentState->processCharacter('e', $tokenizer);
        $commentState->processCharacter('l', $tokenizer);
        $commentState->processCharacter('l', $tokenizer);
        $commentState->processCharacter('o', $tokenizer);

        $data = $tokenizer->getCurrentToken()->getData();

        $this->assertEquals('hello', $data);
    }

    public function testClosingCommentDash()
    {
        $tokenizer = new TestTokenizer();
        $commentState = new CommentState();

        $commentState->processCharacter('-', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\CommentEndDashState', $tokenizer->getState());
    }

    public function testOpeningOfNestedComment()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setCurrentToken(new CommentToken());

        $commentState = new CommentState();
        $commentState->processCharacter('<', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\CommentLessThanSignState', $tokenizer->getState());
        $this->assertEquals('<', $tokenizer->getCurrentToken()->getData());
    }

    public function testEndOfFile()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setCurrentToken(new CommentToken());

        $commentState = new CommentState();
        $commentState->processCharacter(TestTokenizer::END_OF_FILE_CHARACTER, $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(2, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CommentToken', $tokens[0]);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndOfFileToken', $tokens[1]);
    }
}
