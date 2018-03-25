<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\AttributeNameState;
use HtmlParser\Tokenizer\Structs\AttributeStruct;
use HtmlParser\Tokenizer\Tokens\TagToken;
use PHPUnit\Framework\TestCase;

class AttributeNameStateTest extends TestCase
{
    public function testAsciiAttributeName()
    {
        $tagToken = new TagToken();
        $tagToken->setCurrentAttribute(new AttributeStruct());

        $tokenizer = new TestTokenizer();
        $tokenizer->setCurrentToken($tagToken);

        $attributeNameState = new AttributeNameState();

        $attributeNameState->processCharacter('h', $tokenizer);
        $attributeNameState->processCharacter('r', $tokenizer);
        $attributeNameState->processCharacter('e', $tokenizer);
        $attributeNameState->processCharacter('f', $tokenizer);

        $attribute = $tokenizer->getCurrentToken()->getCurrentAttribute();
        $this->assertEquals('href', $attribute->getName());
    }
}