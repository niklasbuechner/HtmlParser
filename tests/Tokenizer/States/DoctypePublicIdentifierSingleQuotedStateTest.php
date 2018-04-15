<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\DoctypePublicIdentifierSingleQuotedState;
use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\DoctypeToken;
use PHPUnit\Framework\TestCase;

class DoctypePublicIdentifierSingleQuotedStateTest extends TestCase
{
    public function testEndOfTag()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new DoctypeToken());

        $doctypePublicIdentifierSingleQuotedState = new DoctypePublicIdentifierSingleQuotedState();
        $doctypePublicIdentifierSingleQuotedState->processCharacter('>', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DataState', $tokenizer->getState());
        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\DoctypeToken', $tokens[0]);
    }

    public function testEndOfFile()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new DoctypeToken());

        $doctypePublicIdentifierSingleQuotedState = new DoctypePublicIdentifierSingleQuotedState();
        $doctypePublicIdentifierSingleQuotedState->processCharacter(Tokenizer::END_OF_FILE_CHARACTER, $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(2, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\DoctypeToken', $tokens[0]);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndOfFileToken', $tokens[1]);
    }

    public function testAppendCharactersToPublicIdentifier()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new DoctypeToken());

        $doctypePublicIdentifierSingleQuotedState = new DoctypePublicIdentifierSingleQuotedState();
        $doctypePublicIdentifierSingleQuotedState->processCharacter('h', $tokenizer);
        $doctypePublicIdentifierSingleQuotedState->processCharacter('e', $tokenizer);
        $doctypePublicIdentifierSingleQuotedState->processCharacter('l', $tokenizer);
        $doctypePublicIdentifierSingleQuotedState->processCharacter('l', $tokenizer);
        $doctypePublicIdentifierSingleQuotedState->processCharacter('o', $tokenizer);

        $this->assertEquals('hello', $tokenizer->getToken()->getPublicIdentifier());
    }

    public function testEndOfPublicIdentifier()
    {
        $tokenizer = new TestTokenizer();
        $doctypePublicIdentifierSingleQuotedState = new DoctypePublicIdentifierSingleQuotedState();

        $doctypePublicIdentifierSingleQuotedState->processCharacter('"', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\AfterDoctypePublicIdentifierState', $tokenizer->getState());
    }
}
