<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\DoctypeSystemIdentifierDoubleQuotedState;
use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\DoctypeToken;
use PHPUnit\Framework\TestCase;

class DoctypeSystemIdentifierDoubleQuotedStateTest extends TestCase
{
    public function testEndOfFile()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new DoctypeToken());

        $doctypeSystemIdentifierDoubleQuotedState = new DoctypeSystemIdentifierDoubleQuotedState();
        $doctypeSystemIdentifierDoubleQuotedState->processCharacter(Tokenizer::END_OF_FILE_CHARACTER, $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(2, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\DoctypeToken', $tokens[0]);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndOfFileToken', $tokens[1]);
    }

    public function testUnexpectedClosingTag()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new DoctypeToken);

        $doctypeSystemIdentifierDoubleQuotedState = new DoctypeSystemIdentifierDoubleQuotedState();
        $doctypeSystemIdentifierDoubleQuotedState->processCharacter('>', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DataState', $tokenizer->getState());
        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\DoctypeToken', $tokens[0]);
    }

    public function testSystemIdentifierCharacter()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new DoctypeToken);

        $doctypeSystemIdentifierDoubleQuotedState = new DoctypeSystemIdentifierDoubleQuotedState();
        $doctypeSystemIdentifierDoubleQuotedState->processCharacter('a', $tokenizer);

        $this->assertEquals('a', $tokenizer->getToken()->getSystemIdentifier());
    }

    public function testEndOfSystemIdentifier()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new DoctypeToken);

        $doctypeSystemIdentifierDoubleQuotedState = new DoctypeSystemIdentifierDoubleQuotedState();
        $doctypeSystemIdentifierDoubleQuotedState->processCharacter('"', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\AfterDoctypeSystemIdentifierState', $tokenizer->getState());
    }
}
