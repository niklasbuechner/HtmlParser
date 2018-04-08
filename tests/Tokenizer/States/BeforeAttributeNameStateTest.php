<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\AttributeNameState;
use HtmlParser\Tokenizer\States\BeforeAttributeNameState;
use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\StartTagToken;
use PHPUnit\Framework\TestCase;

class BeforeAttributeNameStateTest extends TestCase
{
    public function testStartOfAttribute()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setCurrentToken(new StartTagToken);
        $beforeAttributeNameState = new BeforeAttributeNameState();

        $beforeAttributeNameState->processCharacter('h', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\AttributeNameState', $tokenizer->getState());
    }

    public function testSolidus()
    {
        $tokenizer = new TestTokenizer();
        $beforeAttributeNameState = new BeforeAttributeNameState();

        $beforeAttributeNameState->processCharacter('/', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\AfterAttributeNameState', $tokenizer->getState());
    }

    public function testTagEnd()
    {
        $tokenizer = new TestTokenizer();
        $beforeAttributeNameState = new BeforeAttributeNameState();

        $beforeAttributeNameState->processCharacter('>', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\AfterAttributeNameState', $tokenizer->getState());
    }

    public function testEndOfFile()
    {
        $tokenizer = new TestTokenizer();
        $beforeAttributeNameState = new BeforeAttributeNameState();

        $beforeAttributeNameState->processCharacter(Tokenizer::END_OF_FILE_CHARACTER, $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\AfterAttributeNameState', $tokenizer->getState());
    }

    public function testStrayEqualsSign()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setCurrentToken(new StartTagToken);

        $beforeAttributeNameState = new BeforeAttributeNameState();
        $beforeAttributeNameState->processCharacter('=', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\AttributeNameState', $tokenizer->getState());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Structs\AttributeStruct', $tokenizer->getCurrentToken()->getCurrentAttribute());
    }
}
