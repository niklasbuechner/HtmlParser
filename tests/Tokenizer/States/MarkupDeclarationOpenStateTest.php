<?php
namespace HtmlParser\Tests\Tokenizer\States;

use \Exception;
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
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\CommentToken', $tokenizer->getCurrentToken());
    }

    public function testCDATAException()
    {
        try {
            $tokenizer = new TestTokenizer();

            $tokenizer->setState(new MarkupDeclarationOpenState());
            $tokenizer->tokenize('[CDATA[');

            $this->assertEquals(false, true);
        } catch (Exception $exception) {
            $this->assertTrue(true);
        }
    }
}
