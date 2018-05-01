<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\AfterDoctypePublicIdentifierState;
use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\DoctypeToken;
use PHPUnit\Framework\TestCase;

class AfterDoctypePublicIdentifierStateTest extends TestCase
{
    public function testEndOfFile()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new DoctypeToken());

        $afterDoctypePublicIdentifierState = new AfterDoctypePublicIdentifierState();
        $afterDoctypePublicIdentifierState->processCharacter(Tokenizer::END_OF_FILE_CHARACTER, $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(2, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\DoctypeToken', $tokens[0]);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndOfFileToken', $tokens[1]);
        $this->assertTrue($tokens[0]->isInQuirksMode());
    }

    public function testEndOfTag()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new DoctypeToken());

        $afterDoctypePublicIdentifierState = new AfterDoctypePublicIdentifierState();
        $afterDoctypePublicIdentifierState->processCharacter('>', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DataState', $tokenizer->getState());
        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\DoctypeToken', $tokens[0]);
    }

    public function testDoubleQuotedSystemIdentifier()
    {
        $tokenizer = new TestTokenizer();
        $afterDoctypePublicIdentifierState = new AfterDoctypePublicIdentifierState();

        $afterDoctypePublicIdentifierState->processCharacter('"', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DoctypeSystemIdentifierDoubleQuotedState', $tokenizer->getState());
    }

    public function testSingleQuotedSystemIdentifier()
    {
        $tokenizer = new TestTokenizer();
        $afterDoctypePublicIdentifierState = new AfterDoctypePublicIdentifierState();

        $afterDoctypePublicIdentifierState->processCharacter('\'', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DoctypeSystemIdentifierSingleQuotedState', $tokenizer->getState());
    }

    public function testInvalidCharacters()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new DoctypeToken());

        $afterDoctypePublicIdentifierState = new AfterDoctypePublicIdentifierState();
        $afterDoctypePublicIdentifierState->processCharacter('a', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\BogusDoctypeState', $tokenizer->getState());
    }

    public function testIgnoredWhiteSpaces()
    {
        $tokenizer = new TestTokenizer();
        $afterDoctypePublicIdentifierState = new AfterDoctypePublicIdentifierState();

        $afterDoctypePublicIdentifierState->processCharacter(' ', $tokenizer);

        $this->assertNull($tokenizer->getState());
        $this->assertNull($tokenizer->getToken());
        $this->assertCount(0, $tokenizer->getTokenListener()->getEmittedTokens());
    }
}
