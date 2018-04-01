<?php
namespace HtmlParser\Tests\Tokenizer;

use HtmlParser\Tests\TestResources\TestTokenListener;
use HtmlParser\Tokenizer\HtmlTokenizer;
use PHPUnit\Framework\TestCase;

class HtmlTokenizerTest extends TestCase
{
    public function testTokenizerHelloWorld()
    {
        $testListener = new TestTokenListener();
        $tokenizer = new HtmlTokenizer($testListener);
        $tokenizer->tokenize('<hello></hello>');

        $tokens = $testListener->getEmittedTokens();

        $this->assertCount(3, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\StartTagToken', $tokens[0]);
        $this->assertEquals('hello', $tokens[0]->getName());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndTagToken', $tokens[1]);
        $this->assertEquals('hello', $tokens[1]->getName());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndOfFileToken', $tokens[2]);
    }

    public function testTokenizerWithDoubleQuotedAttributes()
    {
        $testListener = new TestTokenListener();
        $tokenizer = new HtmlTokenizer($testListener);
        $tokenizer->tokenize('<a href="http://www.example.com" title="Hello World"></a>');

        $tokens = $testListener->getEmittedTokens();

        $this->assertCount(3, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\StartTagToken', $tokens[0]);
        $this->assertEquals('a', $tokens[0]->getName());

        $attributes = $tokens[0]->getAttributes();

        $this->assertCount(2, $attributes);
        $this->assertEquals('href', $attributes[0]->getName());
        $this->assertEquals('http://www.example.com', $attributes[0]->getValue());
        $this->assertEquals('title', $attributes[1]->getName());
        $this->assertEquals('Hello World', $attributes[1]->getValue());

        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndTagToken', $tokens[1]);
        $this->assertEquals('a', $tokens[1]->getName());

        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndOfFileToken', $tokens[2]);
    }
}
