<?php
namespace HtmlParser\Tests\Tokenizer\States;

use HtmlParser\Tests\TestResources\TestTokenizer;
use HtmlParser\Tokenizer\States\BeforeDoctypeSystemIdentifierState;
use HtmlParser\Tokenizer\Tokenizer;
use HtmlParser\Tokenizer\Tokens\DoctypeToken;
use PHPUnit\Framework\TestCase;

class BeforeDoctypeSystemIdentifierStateTest extends TestCase
{
    public function testIgnoredWhiteSpaces()
    {
        $tokenizer = new TestTokenizer();
        $beforeDoctypeSystemIdentifierState = new BeforeDoctypeSystemIdentifierState();

        $beforeDoctypeSystemIdentifierState->processCharacter(' ', $tokenizer);

        $this->assertNull($tokenizer->getState());
        $this->assertNull($tokenizer->getToken());
        $this->assertCount(0, $tokenizer->getTokenListener()->getEmittedTokens());
    }

    public function testSystemIdentifierDoubleQuoted()
    {
        $tokenizer = new TestTokenizer();
        $beforeDoctypeSystemIdentifierState = new BeforeDoctypeSystemIdentifierState();

        $beforeDoctypeSystemIdentifierState->processCharacter('"', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DoctypeSystemIdentifierDoubleQuotedState', $tokenizer->getState());
    }

    public function testSystemIdentifierSingleQuoted()
    {
        $tokenizer = new TestTokenizer();
        $beforeDoctypeSystemIdentifierState = new BeforeDoctypeSystemIdentifierState();

        $beforeDoctypeSystemIdentifierState->processCharacter('\'', $tokenizer);

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DoctypeSystemIdentifierSingleQuotedState', $tokenizer->getState());
    }

    public function testUnexpectedDoctypeTagEnd()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new DoctypeToken());

        $beforeDoctypeSystemIdentifierState = new BeforeDoctypeSystemIdentifierState();
        $beforeDoctypeSystemIdentifierState->processCharacter('>', $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertInstanceOf('HtmlParser\Tokenizer\States\DataState', $tokenizer->getState());
        $this->assertCount(1, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\DoctypeToken', $tokens[0]);
        $this->assertTrue($tokens[0]->isInQuirksMode());
    }

    public function testEndOfFile()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new DoctypeToken());

        $beforeDoctypeSystemIdentifierState = new BeforeDoctypeSystemIdentifierState();
        $beforeDoctypeSystemIdentifierState->processCharacter(Tokenizer::END_OF_FILE_CHARACTER, $tokenizer);

        $tokens = $tokenizer->getTokenListener()->getEmittedTokens();

        $this->assertCount(2, $tokens);
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\DoctypeToken', $tokens[0]);
        $this->assertTrue($tokens[0]->isInQuirksMode());
        $this->assertInstanceOf('HtmlParser\Tokenizer\Tokens\EndOfFileToken', $tokens[1]);
    }

    public function testInvalidCharacters()
    {
        $tokenizer = new TestTokenizer();
        $tokenizer->setToken(new DoctypeToken());

        $beforeDoctypeSystemIdentifierState = new BeforeDoctypeSystemIdentifierState();
        $beforeDoctypeSystemIdentifierState->processCharacter('a', $tokenizer);

        $this->assertTrue($tokenizer->getToken()->isInQuirksMode());
        $this->assertInstanceOf('HtmlParser\Tokenizer\States\BogusDoctypeState', $tokenizer->getState());
    }
}
