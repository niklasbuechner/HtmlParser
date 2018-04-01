<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\AttributeNameState;
use HtmlParser\Tokenizer\States\BeforeAttributeNameState;
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
}
