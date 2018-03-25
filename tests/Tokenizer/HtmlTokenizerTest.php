<?php
namespace HtmlParser\Tests\Tokenizer;

use HtmlParser\Tests\TestResources\TestTokenListener;
use HtmlParser\Tokenizer\HtmlTokenizer;
use PHPUnit\Framework\TestCase;

class HtmlTokenizerTest extends TestCase
{
    public function testTokenizerHelloWorld()
    {
        $tokenizer = new HtmlTokenizer(new TestTokenListener());
        $result = $tokenizer->tokenize('<hello></hello>');

        $this->assertEquals($result, []);
    }
}
