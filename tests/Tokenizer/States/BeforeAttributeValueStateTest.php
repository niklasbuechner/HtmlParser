<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tokenizer\States\BeforeAttributeValueState;
use HtmlParser\Tokenizer\States\AttributeValueDoubleQuotedState;
use HtmlParser\Tests\TestResources\TestTokenizer;
use PHPUnit\Framework\TestCase;

class BeforeAttributeValueStateTest extends TestCase
{
    public function testDoubleQuotedValueStart()
    {
        $tokenizer = new TestTokenizer();
        $beforeAttributeValueState = new BeforeAttributeValueState();

        $beforeAttributeValueState->processCharacter('"', $tokenizer);

        $this->assertInstanceOf(AttributeValueDoubleQuotedState::class, $tokenizer->getState());
    }
}
