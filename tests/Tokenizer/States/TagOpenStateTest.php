<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\TagNameState;
use HtmlParser\Tokenizer\States\TagOpenState;
use HtmlParser\Tokenizer\Tokens\TagToken;
use PHPUnit\Framework\TestCase;

class TagOpenStateTest extends TestCase
{
    public function testAsciiCharacter()
    {
        $tokenizer = new TestTokenizer();
        $tagOpenState = new TagOpenState();

        $tagOpenState->processCharacter('a', $tokenizer);

        $currentToken = $tokenizer->getCurrentToken();
        $this->assertTrue($tokenizer->getState() instanceof TagNameState);
        $this->assertTrue($currentToken instanceof TagToken);
        $this->assertEquals('a', $currentToken->getName());
    }
}
