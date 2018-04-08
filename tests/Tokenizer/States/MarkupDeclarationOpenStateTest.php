<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\MarkupDeclarationOpenState;
use PHPUnit\Framework\TestCase;

class MarkupDeclarationOpenStateTest extends TestCase
{
    public function testCommentStart()
    {
        $tokenizer = new TestTokenizer();

        $tokenizer->setState(new MarkupDeclarationOpenState());
        $tokenizer->tokenize('--');

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\CommentStartState', $tokenizer->getState());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CommentToken', $tokenizer->getToken());
    }

    public function testDoctypeStart()
    {
        $tokenizer = new TestTokenizer();

        $tokenizer->setState(new MarkupDeclarationOpenState());
        $tokenizer->tokenize('DOCtype');

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DoctypeState', $tokenizer->getState());
    }
}
