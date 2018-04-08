<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\SelfClosingStartTagState;
use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\StartTagToken;
use PHPUnit\Framework\TestCase;

class SelfClosingStartTagStateTest extends TestCase
{
    public function testEndOfTag()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setCurrentToken(new StartTagToken());

        $selfClosingStartTagState = new SelfClosingStartTagState();
        $selfClosingStartTagState->processCharacter('>', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DataState', $tokenizer->getState());
        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\StartTagToken', $tokens[0]);
        $this->assertTrue($tokens[0]->isSelfClosing());
    }

    public function testEndOfLine()
    {
        $tokenizer = new TestTokenizer();
        $selfClosingStartTagState = new SelfClosingStartTagState();

        $selfClosingStartTagState->processCharacter(Tokenizer::END_OF_FILE_CHARACTER, $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndOfFileToken', $tokens[0]);
    }

    public function testStraySlashInTag()
    {
        $tokenizer = new TestTokenizer();
        $selfClosingStartTagState = new SelfClosingStartTagState();

        $selfClosingStartTagState->processCharacter(' ', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\BeforeAttributeNameState', $tokenizer->getState());
    }
}
