<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\DoctypePublicIdentifierDoubleQuotedState;
use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\DoctypeToken;
use PHPUnit\Framework\TestCase;

class DoctypePublicIdentifierDoubleQuotedStateTest extends TestCase
{
    public function testEndOfTag()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new DoctypeToken());

        $doctypePublicIdentifierDoubleQuotedState = new DoctypePublicIdentifierDoubleQuotedState();
        $doctypePublicIdentifierDoubleQuotedState->processCharacter('>', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DataState', $tokenizer->getState());
        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\DoctypeToken', $tokens[0]);
    }

    public function testEndOfFile()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new DoctypeToken());

        $doctypePublicIdentifierDoubleQuotedState = new DoctypePublicIdentifierDoubleQuotedState();
        $doctypePublicIdentifierDoubleQuotedState->processCharacter(Tokenizer::END_OF_FILE_CHARACTER, $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(2, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\DoctypeToken', $tokens[0]);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndOfFileToken', $tokens[1]);
    }

    public function testAppendCharactersToPublicIdentifier()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new DoctypeToken());

        $doctypePublicIdentifierDoubleQuotedState = new DoctypePublicIdentifierDoubleQuotedState();
        $doctypePublicIdentifierDoubleQuotedState->processCharacter('h', $tokenizer);
        $doctypePublicIdentifierDoubleQuotedState->processCharacter('e', $tokenizer);
        $doctypePublicIdentifierDoubleQuotedState->processCharacter('l', $tokenizer);
        $doctypePublicIdentifierDoubleQuotedState->processCharacter('l', $tokenizer);
        $doctypePublicIdentifierDoubleQuotedState->processCharacter('o', $tokenizer);

        $this->assertEquals('hello', $tokenizer->getToken()->getPublicIdentifier());
    }

    public function testEndOfPublicIdentifier()
    {
        $tokenizer = new TestTokenizer();
        $doctypePublicIdentifierDoubleQuotedState = new DoctypePublicIdentifierDoubleQuotedState();

        $doctypePublicIdentifierDoubleQuotedState->processCharacter('"', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\AfterDoctypePublicIdentifierState', $tokenizer->getState());
    }
}
